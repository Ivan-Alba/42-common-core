import React, { useEffect, useRef } from 'react';
import { Unity, useUnityContext } from 'react-unity-webgl';
import { useNavigate } from 'react-router-dom';
import gameService from '../../services/gameService';
import { useTranslation } from 'react-i18next';

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
        loadingProgression
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

    /**
     * Listener: Handle Match Finished event from Unity
     */
    useEffect(() => {
        const handleMatchFinished = (json: string) => {
            // Prevent the emergency abandon logic since the game ended legally
            hasCleanedUp.current = true;

            console.log("Match Over:", JSON.parse(json));

            // Small delay to allow Unity to finish internal processes before unmounting
            setTimeout(() => {
                navigate(`/index`);
            }, 200);
        };

        addEventListener("GameFinished", handleMatchFinished as any);
        return () => {
            removeEventListener("GameFinished", handleMatchFinished as any);
        };
    }, [addEventListener, removeEventListener, navigate]);

    /**
     * Cleanup: Detects tab closure or component unmount to trigger abandonment
     */
    useEffect(() => {
        const handleAbandonment = () => {
            if (isLoaded && !hasCleanedUp.current) {
                hasCleanedUp.current = true;

                // 1. Tell Unity to stop local logic and prepare for shutdown
                if (isLoaded) {
                    sendMessage('GameManager', 'HandleEmergencyQuit');
                }

                // 2. Notify Backend via sendBeacon (Safe for tab closure)
                // This uses the formula defined in gameService (FormData + sendBeacon)
                gameService.abandonMatchEmergency(matchId);
            }
        };

        // Listen for browser-level events (F5, Tab Close, Browser Exit)
        window.addEventListener("beforeunload", handleAbandonment);

        return () => {
            // Listen for React-level events (SPA Navigation)
            window.removeEventListener("beforeunload", handleAbandonment);

            if (isLoaded) {
                handleAbandonment();
            }
        };
    }, [isLoaded, sendMessage, matchId]);

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
                    /* width and height logic to maintain 16:9 aspect ratio while fitting within the viewport */
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