import React, { useEffect, useRef } from 'react';
import { Unity, useUnityContext } from 'react-unity-webgl';
import { useNavigate } from 'react-router-dom';
import gameService from '../../services/gameService';
import { useTranslation } from 'react-i18next';
import userService from '../../services/userService';

interface UnityGameProps {
	token: string;
	matchId: string;
	userId: number;
	onGameLoaded?: () => void;
}

const UnityGame: React.FC<UnityGameProps> = ({ token, matchId, userId, onGameLoaded }) => {
	const navigate = useNavigate();
	const { t } = useTranslation();
	const isMounted = useRef(true);
	const hasCleanedUp = useRef(false);

	/* Unity Engine Configuration */
	const {
		unityProvider,
		sendMessage,
		addEventListener,
		removeEventListener,
		isLoaded,
		loadingProgression,
		unload
	} = useUnityContext({
		loaderUrl: "/game/Build/NexusNineBuild.loader.js",
		dataUrl: "/game/Build/NexusNineBuild.data.gz",
		frameworkUrl: "/game/Build/NexusNineBuild.framework.js.gz",
		codeUrl: "/game/Build/NexusNineBuild.wasm.gz",
		companyName: "Transcendence",
		productName: "NexusNine",
		productVersion: "0.7",
	});

	useEffect(() => {
		isMounted.current = true;
		return () => {
			isMounted.current = false;
		};
	}, []);

	/**
	 * Handshake: Initialize Unity Match with session data
	 */
	useEffect(() => {
		if (isLoaded && isMounted.current) {
			if (onGameLoaded) {
				onGameLoaded();
			}
			const initData = { token, matchId, userId };
			sendMessage('NetworkManager', 'InitializeMatch', JSON.stringify(initData));
		}
	}, [isLoaded, token, matchId, userId, sendMessage, onGameLoaded]);

	/* Listener: Handle Match Finished event from Unity */
	useEffect(() => {
		const handleMatchFinished = (json: string) => {
			hasCleanedUp.current = true;
			//console.log("Match Over:", JSON.parse(json));
			setTimeout(() => {
				navigate(`/index`);
			}, 200);
		};

		addEventListener("GameFinished", handleMatchFinished as any);
		return () => {
			removeEventListener("GameFinished", handleMatchFinished as any);
		};
	}, [addEventListener, removeEventListener, navigate]);

	/* Closing tab or refreshing, use Beacon because the browser is being destroyed */
	useEffect(() => {
		const onBeforeUnload = () => {
			if (isLoaded && !hasCleanedUp.current) {
				hasCleanedUp.current = true;
				sendMessage('GameManager', 'HandleEmergencyQuit');
				gameService.abandonMatchEmergency(matchId);
			}
		};
		window.addEventListener("beforeunload", onBeforeUnload);
		return () => window.removeEventListener("beforeunload", onBeforeUnload);
	}, [isLoaded, matchId, sendMessage]);

	/* Back Button & Exit Button, use Axios and Await to guarantee penalty application before navigating to index */
	useEffect(() => {
		window.history.pushState(null, "", window.location.href);

		const waitForPenaltyUpdate = async () => {
			for (let i = 0; i < 5; i++) {
				try {
					const data = await userService.getProfile(userId);

					if ((data.penalty_remaining_seconds ?? 0) > 0) {
						return true;
					}
				} catch (e) { }

				/* Wait 300ms between attempts */
				await new Promise(res => setTimeout(res, 300));
			}

			return false;
		};
		const executeSafeExit = async () => {
			if (!isLoaded) return;

			if (!hasCleanedUp.current) {
				hasCleanedUp.current = true;

				sendMessage('GameManager', 'HandleEmergencyQuit');

				try {
					await gameService.abandonMatch(matchId);
				} catch (e) { }
			}

			try {
				await unload();
			} catch { }

			await waitForPenaltyUpdate();

			navigate('/index');
		};

		const handleBackButton = () => {
			window.history.pushState(null, "", window.location.href);
			executeSafeExit();
		};

		window.addEventListener('popstate', handleBackButton);
		window.addEventListener('trigger-safe-exit', executeSafeExit);

		return () => {
			window.removeEventListener('popstate', handleBackButton);
			window.removeEventListener('trigger-safe-exit', executeSafeExit);
		};
	}, [isLoaded, unload, navigate, sendMessage, matchId]);

	return (
		<div className="relative flex items-center justify-center w-full h-screen bg-black overflow-hidden">

			{/* Overlay Loading Screen */}
			{!isLoaded && (
				<div className="absolute inset-0 z-10 flex flex-col items-center justify-center bg-black">
					<div className="w-16 h-16 border-4 border-brand-500/30 border-t-brand-500 rounded-full animate-spin mb-4"></div>
					<p className="text-white font-bold text-xl drop-shadow-lg">{t('game.loading')}</p>
					<p className="text-brand-400 font-mono text-lg mt-2">
						{Math.round(loadingProgression * 100)}%
					</p>
				</div>
			)}

			{/* Main Unity Render Canvas */}
			<Unity
				unityProvider={unityProvider}
				style={{
					width: "min(100vw, 100vh * (16 / 9))",
					height: "min(100vh, 100vw * (9 / 16))",
					background: "#000000",
					boxShadow: '0 0 30px rgba(0,0,0,0.7)'
				}}
				devicePixelRatio={window.devicePixelRatio || 1}
			/>
		</div>
	);
};

export default UnityGame;