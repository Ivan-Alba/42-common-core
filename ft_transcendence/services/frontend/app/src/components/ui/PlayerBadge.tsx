import { t } from "i18next";
import { FaUser } from "react-icons/fa";
import userService from "../../services/userService";

interface PlayerBadgeProps {
    avatar?: string;
    name: string;
    isCurrentUser?: boolean;
    className?: string;
}

const PlayerBadge = ({ avatar, name, isCurrentUser = false, className = "" }: PlayerBadgeProps) => {
	const avatarUrl = userService.getFullAvatarUrl(avatar);
    return (
        /* Main Container centered */
        <div className={`flex items-center justify-center ${className}`}>
            
            {/* LEFT SIDE: Avatar + Space */}
            {/* Fixed width of 120px and content aligned to the end (right) */}
            <div className="w-30 flex justify-end items-center pr-4">
                <div className="w-10 h-10 shrink-0 rounded-full border border-white/10 bg-dark-800 overflow-hidden flex items-center justify-center">
                    {avatarUrl ? (
                        <img src={avatarUrl} alt={name} className="w-full h-full object-cover" />
                    ) : (
                        <FaUser className="text-slate-600 text-xs" />
                    )}
                </div>
            </div>

            {/* CENTER: Vertical Line */}
            <div className="shrink-0 w-px h-8 bg-white/10"></div>

            {/* RIGHT SIDE: Name + Badge */}
            {/* Same fixed width of 120px to maintain balance, aligned to the start (left) */}
            <div className="w-30 flex justify-start items-center pl-4">
                <div className="flex flex-col items-start min-w-0">
                    <span 
                        className={`font-bold truncate text-sm md:text-base leading-tight max-w-27.5 ${isCurrentUser ? "text-brand-400" : "text-white"}`}
                        title={name}
                    >
                        {name}
                    </span>
                    {isCurrentUser && (
                        <span className="text-[10px] bg-brand-500 text-white px-1.5 rounded font-bold tracking-wider mt-0.5">
                            {t('common.you')}
                        </span>
                    )}
                </div>
            </div>
        </div>
    );
};

export default PlayerBadge;