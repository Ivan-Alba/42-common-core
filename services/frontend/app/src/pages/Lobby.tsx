import { useEffect, useState, useRef } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { useAuth } from '../context/AuthContext';
import { useSocket } from '../context/SocketContext';
import DashboardLayout from '../components/layouts/DashboardLayout';
import { FaRobot, FaUserSecret, FaTimes, FaCircleNotch, FaTrophy, FaUsers } from 'react-icons/fa';
import gameService, { type MatchData } from '../services/gameService';
import userService from "../services/userService";

const Lobby = () => {
    const { t } = useTranslation();
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();
    const { user } = useAuth();
    const echo = useSocket();

    const mode = searchParams.get('mode') || 'unknown';
    const [isMatchFound, setIsMatchFound] = useState(false);
    const [isLeaving, setIsLeaving] = useState(false);
    const [opponent, setOpponent] = useState<MatchData['opponent'] | null>(null);

    // Ref to prevent double execution of the REST call
    const matchmakingStarted = useRef(false);

    const getModeDetails = () => {
        if (mode === 'CAMPAIGN_1') return { title: 'Campaign 1 PVE', icon: <FaRobot className="text-brand-500 text-4xl mb-2" /> };
        if (mode === 'CAMPAIGN_2') return { title: 'Campaign 2 PVE', icon: <FaRobot className="text-brand-500 text-4xl mb-2" /> };
        if (mode === 'CAMPAIGN_3') return { title: 'Campaign 3 PVE', icon: <FaRobot className="text-brand-500 text-4xl mb-2" /> };
        if (mode === 'CAMPAIGN_4') return { title: 'Campaign 4 PVE', icon: <FaRobot className="text-brand-500 text-4xl mb-2" /> };
        if (mode === 'PVP_RANKED') return { title: 'Ranked Match', icon: <FaTrophy className="text-warning text-4xl mb-2" /> };
        if (mode === 'PVP_CASUAL_LIMITED') return { title: `Casual Match (Limited)`, icon: <FaUsers className="text-brand-500 text-4xl mb-2" /> };
        if (mode === 'PVP_CASUAL_UNLIMITED') return { title: `Casual Match (Unlimited)`, icon: <FaUsers className="text-brand-500 text-4xl mb-2" /> };
        return { title: 'Unknown Mode', icon: null };
    };

    const { title, icon } = getModeDetails();
    const isPvE = mode === 'campaign';

    useEffect(() => {
        const userId = sessionStorage.getItem('unity_user_id');

        // 1. If infrastructure is not ready, we stop here.
        if (!echo || !userId || !user) return;

        let isMounted = true;
        const privateChannel = `user.${userId}`;
        const channel = echo.private(privateChannel);

        // 2. REGISTER LISTENER IMMEDIATELY
        console.log(`[Lobby] Registering listener for .match.found on ${privateChannel}`);
        channel.listen('.match.found', (data: any) => {
            if (!isMounted) return;

            console.log("[Lobby] EVENT RECEIVED: .match.found", data);
            setIsMatchFound(true);

            if (data.opponent) {
                setOpponent({
                    id: data.opponent.id,
                    username: data.opponent.username,
                    avatar: data.opponent.avatar,
                });
            }

            setTimeout(() => {
                if (isMounted) navigate(`/game/${data.match_uuid}`);
            }, 2500);
        });

        // 3. JOIN QUEUE
        const startQueue = async () => {
            // Guard to ensure we only call joinQueue once per session
            if (matchmakingStarted.current) return;
            matchmakingStarted.current = true;

            try {
                console.log(`[REST] Joining queue for mode: ${mode}`);
                await gameService.joinQueue(mode);
                console.log("[REST] Successfully in queue.");
            } catch (error: any) {
                // IMPORTANT: If we get a 403 (penalty), we MUST navigate back
                // regardless of isMounted if we want the user to be redirected out of the lobby.
                // However, since navigate only works if the component is in the tree, 
                // we check isMounted but we handle the error priority.
                if (error.response?.status === 403 || error.response?.status === 409) {
                    console.warn("[Lobby] Access forbidden: ", error.response.data.error);
                    navigate('/index');
                    return;
                }

                if (isMounted) {
                    console.error("[Lobby] Unexpected Queue Error:", error);
                    navigate('/index');
                }
            }
        };

        startQueue();

        // CLEANUP
        return () => {
            isMounted = false;
            console.log(`[Lobby] Stopping listener on ${privateChannel}`);
            channel.stopListening('.match.found');
        };
    }, [echo, mode, navigate, user]);

    const handleCancel = async () => {
        if (isLeaving || isMatchFound) return;
        setIsLeaving(true);
        try {
            await gameService.leaveQueue();
            navigate('/index');
        } catch (error) {
            console.error("Failed to leave queue:", error);
            setIsLeaving(false);
        }
    };

    // Render Guard: To avoid the "double flash", we return a consistent loading state
    // if Echo isn't ready. This ensures the rest of the component only renders once.
    if (!echo) {
        return (
            <DashboardLayout isCentered={true}>
                <div className="flex flex-col items-center">
                    <FaCircleNotch className="animate-spin text-brand-500 text-4xl mb-4" />
                    <p className="text-slate-400">Connecting to game server...</p>
                </div>
            </DashboardLayout>
        );
    }

    return (
        <DashboardLayout isCentered={true}>
            <div className="max-w-3xl w-full mx-auto animate-fade-in-up">
                <div className="text-center mb-10">
                    <div className="flex justify-center">{icon}</div>
                    <h1 className="text-3xl font-bold text-white mb-2 tracking-wide">{title}</h1>
                    <p className="text-slate-400">
                        {isMatchFound
                            ? "¡Partida lista! Preparando el tablero..."
                            : isPvE ? "Cargando entorno de simulación..." : "Buscando oponente de tu nivel..."}
                    </p>
                </div>

                <div className="flex flex-col md:flex-row items-center justify-center gap-8 mb-12 relative">
                    {/* User Profile Card */}
                    <div className="glass-panel p-6 flex flex-col items-center w-48 relative z-10 border-brand-500/30 shadow-[0_0_20px_rgba(59,130,246,0.1)]">
                        <div className="w-20 h-20 rounded-full bg-brand-500/20 flex items-center justify-center border-2 border-brand-500 mb-4 overflow-hidden">
                            {user?.avatar ? (
                                <img
                                    src={userService.getFullAvatarUrl(user.avatar)}
                                    alt={user.username}
                                    className="w-full h-full object-cover"
                                />
                            ) : (
                                <span className="text-2xl font-bold text-brand-500">
                                    {user?.username?.charAt(0).toUpperCase()}
                                </span>
                            )}
                        </div>
                        <h3 className="text-lg font-bold text-white text-center w-full truncate">
                            {user?.username}
                        </h3>
                        <span className="text-xs text-brand-400 font-medium bg-brand-500/10 px-3 py-1 rounded-full mt-2">
                            Ready
                        </span>
                    </div>

                    <div className="flex flex-col items-center justify-center z-10">
                        <div className="w-16 h-16 rounded-full bg-dark-900 border border-white/10 flex items-center justify-center shadow-lg relative">
                            {!isMatchFound && (
                                <>
                                    <div className="absolute inset-0 rounded-full border border-brand-500/50 animate-ping opacity-75"></div>
                                    <div className="absolute -inset-4 rounded-full border border-brand-500/20 animate-ping opacity-50" style={{ animationDelay: '0.2s' }}></div>
                                </>
                            )}
                            <span className={`font-black italic text-xl ${isMatchFound ? 'text-brand-500' : 'text-slate-500'}`}>VS</span>
                        </div>
                    </div>

                    {/* Opponent Profile Card */}
                    <div className={`glass-panel p-6 flex flex-col items-center w-48 relative z-10 transition-all duration-500 ${isMatchFound ? 'border-danger/30 shadow-[0_0_20px_rgba(239,68,68,0.1)]' : 'border-white/5 opacity-70'}`}>
                        <div className={`w-20 h-20 rounded-full flex items-center justify-center border-2 mb-4 overflow-hidden transition-all duration-500
                            ${isMatchFound
                                ? 'bg-danger/20 border-danger text-danger'
                                : 'bg-dark-900 border-white/10 text-slate-600'}`}
                        >
                            {isMatchFound && opponent?.avatar ? (
                                <img src={opponent.avatar} alt={opponent.username} className="w-full h-full object-cover" />
                            ) : isMatchFound && isPvE ? (
                                <FaRobot size={32} />
                            ) : isMatchFound && !isPvE ? (
                                <FaUserSecret size={32} />
                            ) : (
                                <FaCircleNotch className="animate-spin" size={28} />
                            )}
                        </div>
                        <h3 className="text-lg font-bold text-white text-center w-full truncate">
                            {isMatchFound && opponent ? opponent.username : 'Searching...'}
                        </h3>
                        <span className={`text-xs font-medium px-3 py-1 rounded-full mt-2 transition-colors duration-500
                            ${isMatchFound ? 'bg-danger/10 text-danger' : 'bg-white/5 text-slate-500'}`}>
                            {isMatchFound ? 'Ready' : 'Waiting'}
                        </span>
                    </div>
                </div>

                <div className="flex justify-center">
                    <button
                        onClick={handleCancel}
                        disabled={isMatchFound || isLeaving}
                        className={`btn-icon px-8 py-3 font-bold rounded-xl transition-all duration-300 flex items-center gap-2
                            ${isMatchFound
                                ? 'bg-success/20 text-success border border-success/50 cursor-not-allowed'
                                : isLeaving
                                    ? 'bg-dark-900 border border-slate-500 text-slate-500 cursor-wait'
                                    : 'bg-dark-900 border border-danger/50 text-danger hover:bg-danger hover:text-white hover:shadow-[0_0_15px_rgba(239,68,68,0.4)]'}`}
                    >
                        {isMatchFound ? (
                            <>Preparando el Tablero...</>
                        ) : isLeaving ? (
                            <>
                                <FaCircleNotch className="animate-spin" />
                                Cancelando...
                            </>
                        ) : (
                            <>
                                <FaTimes />
                                {t('common.cancel', 'Cancelar Búsqueda')}
                            </>
                        )}
                    </button>
                </div>
            </div>
        </DashboardLayout>
    );
};

export default Lobby;