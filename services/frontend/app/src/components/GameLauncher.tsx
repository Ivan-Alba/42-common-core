import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { GiCardPlay } from 'react-icons/gi';
import gameService from '../services/gameService'; 

const GameLauncher: React.FC = () => {
    const navigate = useNavigate();
    const [isLoading, setIsLoading] = useState<boolean>(false);

    const handleStartGame = async (): Promise<void> => {
        setIsLoading(true);
        try {
            // Llamamos al servicio pasando "PVE" como modo
            const data = await gameService.joinQueue("PVE");

            // Según tu controlador, el servidor devuelve match_uuid directamente en PVE
            if (data && data.match_uuid) {
                navigate(`/game/${data.match_uuid}`);
            } else {
                // Si por alguna razón el servidor no devuelve el UUID inmediatamente
                console.warn("Matchmaking iniciado, pero no se recibió UUID inmediato.");
            }
        } catch (error) {
            console.error("Error al iniciar matchmaking:", error);
            alert("Error crítico: No se pudo conectar con el servicio de matchmaking.");
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="mb-8 w-full">
            <button 
                onClick={handleStartGame}
                disabled={isLoading}
                className={`w-full bg-linear-to-r from-red-600 to-orange-500 hover:from-red-500 hover:to-orange-400 text-white font-black text-xl py-6 rounded-2xl shadow-[0_0_20px_rgba(239,68,68,0.5)] transform hover:scale-[1.02] transition-all flex justify-center items-center gap-3 uppercase tracking-widest ${
                    isLoading ? 'opacity-50 cursor-not-allowed animate-pulse' : ''
                }`}
            >
                <GiCardPlay size={32} />
                {isLoading ? 'CREANDO SESIÓN...' : 'FORZAR ENTRADA AL JUEGO (UNITY TEST)'}
            </button>
        </div>
    );
};

export default GameLauncher;