import { useParams, Navigate } from 'react-router-dom';
import UnityGame from '../components/ui/UnityGame';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { FaArrowLeft } from 'react-icons/fa';
import { MdScreenRotation } from "react-icons/md";

const Game = () => {
    const { matchId } = useParams();
    const { t } = useTranslation();
    
    const token = sessionStorage.getItem('unity_auth_token');
    const userId = sessionStorage.getItem('unity_user_id');

    const [isUnityLoaded, setIsUnityLoaded] = useState(false);

    if (!token || !matchId || !userId) {
        return <Navigate to="/index" />;
    }

    return (
        <div className="w-screen h-screen bg-black overflow-hidden relative flex items-center justify-center">
            
            {/* Mobile definition*/}
            <div className="portrait:flex landscape:hidden fixed inset-0 z-9999 bg-dark-900 flex-col items-center justify-center text-center p-6">
                <MdScreenRotation size={80} className="text-brand-500 mb-6 animate-pulse" />
                <h2 className="text-3xl font-bold text-white mb-3">
                    {t('game.rotate_device')}
                </h2>
                <p className="text-slate-400 max-w-xs">
                    {t('game.landscape_required')}
                </p>
            </div>

            {/* Exit Button to index */}
            {isUnityLoaded && (
                <button 
                    onClick={() => window.dispatchEvent(new Event('trigger-safe-exit'))}
                    className="absolute top-6 right-6 z-50 bg-dark-900/50 hover:bg-danger text-white px-4 py-2 sm:px-5 sm:py-3 rounded-xl backdrop-blur-md border border-white/10 transition-all shadow-lg flex items-center gap-3 group"
                >
                    <FaArrowLeft className="group-hover:-translate-x-1 transition-transform" /> 
                    <span className="font-bold text-sm uppercase tracking-wider hidden sm:block">
                        {t('common.exit')}
                    </span>
                </button>
            )}

            {/* Game Component */}
            <UnityGame 
                token={token} 
                matchId={matchId} 
                userId={userId as any} 
                onGameLoaded={() => setIsUnityLoaded(true)} 
            />
            
        </div>
    );
};

export default Game;