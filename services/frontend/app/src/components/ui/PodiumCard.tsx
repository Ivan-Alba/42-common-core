import { FaUser, FaCrown, FaMinus } from "react-icons/fa";
import { HiTrendingUp, HiTrendingDown } from "react-icons/hi";
import { useTranslation } from 'react-i18next';
import { useAuth } from '../../context/AuthContext';
import type { PodiumCardProps } from "../../models/RankingUser";

const PodiumCard = ({ player, place, isWinner = false, delay = "0s" }: PodiumCardProps) => {
    const { t } = useTranslation();
    const { user: authUser } = useAuth();

    // Check if this player is the logged-in user
    const isCurrentUser = authUser?.id ? Number(authUser.id) === Number(player.id) : false;
    
    const borderColor = isWinner ? "border-brand-500" : place === 2 ? "border-slate-400" : "border-orange-700";
    
    /* Heights: We maintain the step effect on desktop, but adapt on mobile. */
    const heightClass = isWinner 
        ? "h-auto py-8 md:py-0 md:h-[340px]" 
        : "h-auto py-6 md:py-0 md:h-[280px]"; 
        
    const crownColor = isWinner ? "text-amber-400" : place === 2 ? "text-slate-300" : "text-orange-400";
    const badgeColor = isWinner ? "bg-amber-400" : place === 2 ? "bg-slate-300" : "bg-orange-400";

    /* Unified avatar size: Always large */
    const avatarSizeClass = "w-28 h-28 md:w-32 md:h-32";

    // Trend Function for rendering the trend icon based on last rank position
    const renderTrend = () => {
        if (player.stats.last_rank_pos == null) return null;
        
        if (player.stats.last_rank_pos > place) {
            return <HiTrendingUp className="text-success text-lg" title="Subió de puesto" />;
        } else if (player.stats.last_rank_pos < place) {
            return <HiTrendingDown className="text-danger text-lg" title="Bajó de puesto" />;
        } else {
            return <FaMinus className="text-slate-500 text-sm" title="Mantuvo el puesto" />;
        }
    };

    return (
        <div 
            className={`
                relative flex flex-col items-center justify-end 
                w-full 
                ${heightClass} 
                animate-fade-in-up
                mb-0
            `}
            style={{ animationDelay: delay }}
        >
            {/* Floating Avatar */}
            <div className="relative md:absolute md:top-0 flex flex-col items-center z-20 mb-4 md:mb-0 transition-transform hover:-translate-y-2 duration-300">
                {isWinner && (
                    <FaCrown className={`text-4xl mb-2 ${crownColor} animate-bounce drop-shadow-lg`} />
                )}
                
                <div className="relative">
                    {/* We apply the unified size here */}
                    <div className={`
                        rounded-full border-4 ${borderColor} bg-dark-900 overflow-hidden shadow-2xl 
                        ${avatarSizeClass}
                    `}>
                        {player.avatar ? (
                            <img src={player.avatar} alt={player.username} className="w-full h-full object-cover" />
                        ) : (
                            <div className="w-full h-full flex items-center justify-center text-slate-600">
                                {/* Unified fallback icon size */}
                                <FaUser size={40} />
                            </div>
                        )}
                    </div>

                    {/* Number Badge */}
                    <div className={`
                        absolute -bottom-3 left-1/2 -translate-x-1/2 
                        w-8 h-8 md:w-10 md:h-10 flex items-center justify-center 
                        rounded-full font-black text-dark-900 border-2 border-dark-900 z-30
                        ${badgeColor} ${isWinner ? 'text-lg' : 'text-base'}
                    `}>
                        {place}
                    </div>
                </div>
            </div>

            {/* Glass Box */}
            <div className={`
                w-full md:h-[70%] glass-panel rounded-xl md:rounded-t-2xl md:rounded-b-xl md:border-t-0 
                flex flex-col justify-end items-center 
                pb-4 pt-10 md:pt-16 relative overflow-hidden
                bg-linear-to-b from-white/5 to-transparent
                ${isWinner ? 'border-brand-500/30' : 'border-white/5'}
                ${isCurrentUser ? 'bg-brand-500/5 border-brand-500/50' : ''}
            `}>
                <h3 className={`font-bold ${isWinner ? 'text-2xl text-white' : 'text-xl text-slate-200'} mb-1 flex items-center gap-2`}>
                    {player.username}
                    {/* Badge of "YOU" if this is the logged-in user */}
                    {isCurrentUser && (
                        <span className="text-[10px] bg-brand-500 text-white px-1.5 py-0.5 rounded font-black tracking-wider uppercase">
                            {t('common.you')}
                        </span>
                    )}
                </h3>
                
                {/* Points + Trend Container */}
                <div className="flex items-center gap-2 mb-2">
                    <p className="text-brand-400 font-mono font-bold text-lg">
                        {player.stats.ranked_points} <span className="text-xs text-slate-500 font-sans">PTS</span>
                    </p>
                    <div className="bg-black/30 rounded px-1.5 py-0.5 flex items-center justify-center">
                        {renderTrend()}
                    </div>
                </div>

                <div className="flex items-center gap-3 text-[10px] md:text-xs text-slate-400 bg-black/20 px-3 py-1.5 rounded-full font-bold tracking-wider">
					<span className="flex items-center gap-1">
						{t('ranking.stats_initials.win')}: <span className="text-success">{player.stats.wins || 0}</span>
					</span>
					<span className="text-slate-600/50">|</span>
					<span className="flex items-center gap-1">
						{t('ranking.stats_initials.loss')}: <span className="text-danger">{player.stats.losses || 0}</span>
					</span>
					<span className="text-slate-600/50">|</span>
					<span className="flex items-center gap-1">
						{t('ranking.stats_initials.draw')}: <span className="text-warning">{player.stats.draws || 0}</span>
					</span>
				</div>
            </div>

            {/* Glow effect */}
            {isWinner && (
                <div className="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-20 bg-brand-500/20 blur-[50px] -z-10"></div>
            )}
            
            {/* Optional: Extra Glow if you are the one in the podiom */}
            {isCurrentUser && !isWinner && (
                <div className="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-10 bg-brand-500/10 blur-[30px] -z-10"></div>
            )}
        </div>
    );
};

export default PodiumCard;