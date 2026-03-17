import React, { useEffect } from 'react';
import { Unity, useUnityContext } from 'react-unity-webgl';

interface UnityGameProps {
    token: string;
    matchId: string;
}

const UnityGame: React.FC<UnityGameProps> = ({ token, matchId }) => {
    console.log("[UnityGame] Props received:", { token, matchId });
    /* Motor Unity starting */
    const { unityProvider, sendMessage, addEventListener, removeEventListener, isLoaded, loadingProgression } = useUnityContext({
		loaderUrl: "/game/Build/NexusNineBuild.loader.js",
        dataUrl: "/game/Build/NexusNineBuild.data.gz",
        frameworkUrl: "/game/Build/NexusNineBuild.framework.js.gz",
        codeUrl: "/game/Build/NexusNineBuild.wasm.gz",
    });

	/* Function to handle match finished event, data from Unity to React */
    useEffect(() => {
        /* Function to handle match finished event */
        const handleMatchFinished = (json: string) => {
            const results = JSON.parse(json);
            console.log("Match Over:", results);
            // navigate('/postgame', { state: results })
        };

        /* Event Listener to data from Unity */
        addEventListener("GameFinished", handleMatchFinished as any);

        // Cleanup al desmontar el componente (importantísimo para que no haya fugas de memoria)
        return () => {
            removeEventListener("GameFinished", handleMatchFinished as any);
        };
    }, [addEventListener, removeEventListener]);

    /* React to Unity Handshake still Unity is ready */
    useEffect(() => {
        if (isLoaded) {
            const initData = { token, matchId };

            /* Call to InitializeMatch with token and matchId */
            sendMessage('WebClient', 'InitializeMatch', JSON.stringify(initData));
        }
    }, [isLoaded, token, matchId, sendMessage]);

    return (
        <div className="relative flex items-center justify-center w-full h-full bg-black overflow-hidden">
            
            {/* Loading Screen */}
            {!isLoaded && (
                <div className="absolute inset-0 z-10 flex flex-col items-center justify-center bg-black">
                    <div className="w-16 h-16 border-4 border-brand-500/30 border-t-brand-500 rounded-full animate-spin mb-4"></div>
                    <p className="text-white font-bold text-xl drop-shadow-lg">Iniciando Nexus Nine...</p>
                    <p className="text-brand-400 font-mono text-lg mt-2">{Math.round(loadingProgression * 100)}%</p>
                </div>
            )}

            {/* Unity Canvas */}
            <Unity 
                unityProvider={unityProvider} 
                style={{
                    aspectRatio: '16/9',
                    width: '100% !important',
                    height: '100% !important',
                    maxWidth: '100vw',
                    maxHeight: '100vh',
                    objectFit: 'contain',
                    background: '#000000',
					boxShadow: '0 0 30px rgba(0,0,0,0.7)'
                }}
                devicePixelRatio={window.devicePixelRatio || 1} 
            />
        </div>
    );
};

export default UnityGame;