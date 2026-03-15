import { useEffect } from "react";
import { Unity, useUnityContext } from "react-unity-webgl";

interface UnityGameProps {
    matchId: string | number;
    onGameOver?: (matchResult: any) => void;
}

const UnityGame = ({ matchId, onGameOver }: UnityGameProps) => {
    // 1. Configuramos el motor de Unity (asegúrate de que la carpeta Build está en public/Build)
    const { unityProvider, sendMessage, isLoaded, addEventListener, removeEventListener } = useUnityContext({
        loaderUrl: "/Build/NexusNineBuild.loader.js",
        dataUrl: "/Build/NexusNineBuild.data.unityweb",
        frameworkUrl: "/Build/NexusNineBuild.framework.js.unityweb",
        codeUrl: "/Build/NexusNineBuild.wasm.unityweb",
    });

    // 2. ENVIAR DATOS A UNITY (El ID de la partida)
    useEffect(() => {
        if (isLoaded && matchId) {
            console.log(`Enviando MatchID ${matchId} a Unity...`);
            sendMessage("NetworkManager", "SetMatchId", matchId.toString());
        }
    }, [isLoaded, matchId, sendMessage]);

    // 3. RECIBIR DATOS DE UNITY
    useEffect(() => {
        const handleGameOver = (resultadoJSON: string) => {
            console.log("¡Partida terminada desde Unity!", resultadoJSON);
            const resultado = JSON.parse(resultadoJSON);
            
            if (onGameOver) {
                onGameOver(resultado);
            }
        };

        // type assertion necesario porque la librería espera parámetros específicos
        addEventListener("OnGameOver", handleGameOver as any);

        return () => {
            removeEventListener("OnGameOver", handleGameOver as any);
        };
    }, [addEventListener, removeEventListener, onGameOver]);

    return (
        <div className="w-full h-full flex items-center justify-center bg-black relative">
            {!isLoaded && (
                <div className="absolute inset-0 flex flex-col items-center justify-center text-white z-10">
                    <div className="w-12 h-12 border-4 border-brand-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                    <p className="font-vecna text-xl animate-pulse">Cargando Nexo...</p>
                </div>
            )}
            
            <Unity 
                unityProvider={unityProvider} 
                className="w-full h-auto aspect-video max-w-[100vw] max-h-screen object-contain shadow-[0_0_30px_rgba(0,0,0,0.7)]"
            />
        </div>
    );
};

export default UnityGame;