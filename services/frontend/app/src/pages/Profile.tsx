import { useEffect, useState, useMemo, useCallback } from 'react';
import { useParams, Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { FaGamepad, FaExclamationTriangle, FaChevronDown, FaChevronUp } from "react-icons/fa";
import { MdHistory, MdInsertChartOutlined, MdChevronLeft, MdChevronRight } from "react-icons/md";
import { HiOutlineTrophy } from "react-icons/hi2";
import DashboardLayout from '../components/layouts/DashboardLayout';
import LoadingState from '../components/ui/LoadingState';
import PlayerBadge from '../components/ui/PlayerBadge';
import type { UserProfile } from '../models/User';
import type { CardData } from '../models/CardData';
import { useAuth } from '../context/AuthContext';
import userService from '../services/userService';
import ProfileHeader from '../components/ui/ProfileHeader';
import StatBox from '../components/ui/StatsBox';
import AchievementCard from '../components/ui/AchievementCard';

const Profile = () => {
    const { t, i18n } = useTranslation();
    const { id } = useParams<{ id: string }>();

    const { user: authUser, isLoading: isAuthLoading } = useAuth();
    const isOwnProfile = Boolean(!id || (authUser && Number(id) === Number(authUser.id)));

    const [profileData, setProfileData] = useState<UserProfile | null>(null);
    const [allCards, setAllCards] = useState<CardData[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [showAllAchievements, setShowAllAchievements] = useState(false);
    const [relationStatus, setRelationStatus] = useState<'none' | 'pending' | 'accepted' | 'outgoing' | 'rejected'>('none');

    const [currentPage, setCurrentPage] = useState(1);
    const ITEMS_PER_PAGE = 5;

    useEffect(() => {
        setCurrentPage(1);
    }, [id]);

    const sortedAchievements = useMemo(() => {
        if (!profileData?.achievements) return [];
        return [...profileData.achievements].sort((a, b) => {
            if (a.is_unlocked !== b.is_unlocked) return a.is_unlocked ? -1 : 1;
            if (a.is_unlocked && b.is_unlocked) {
                if (a.claimed !== b.claimed) return a.claimed ? 1 : -1;
            }
            return 0;
        });
    }, [profileData?.achievements]);

    const displayedAchievements = showAllAchievements
        ? sortedAchievements
        : sortedAchievements.slice(0, 4);

    const fetchData = useCallback(async (isActive: boolean, showGlobalLoading: boolean) => {
        if (showGlobalLoading) setIsLoading(true);

        try {
            const targetId = isOwnProfile ? authUser?.id : id;
            if (!targetId) throw new Error("No user specified");

            const currentLang = i18n.language?.split('-')[0] || 'es';

            const [userData, catalogData] = await Promise.all([
                userService.getProfile(targetId, currentLang),
                userService.getAllCards(currentLang)
            ]);

            if (isActive) {
                const formattedCatalog: CardData[] = catalogData.map((card: any) => {
                    let mappedRarity = String(card.rarity).toLowerCase();
                    if (mappedRarity.includes('golden')) mappedRarity = 'legendary';
                    return {
                        id: Number(card.id),
                        name: card.name || '',
                        description: card.description || '',
                        category: card.category,
                        rarity: mappedRarity as CardData['rarity'],
                        blue_url: card.blue_url || '',
                        red_url: card.red_url || '',
                        blue_artwork: card.blue_artwork || '',
                        red_artwork: card.red_artwork || '',
                        stats: {
                            top: card.stats?.top === 10 ? 'A' : card.stats?.top,
                            right: card.stats?.right === 10 ? 'A' : card.stats?.right,
                            bottom: card.stats?.bottom === 10 ? 'A' : card.stats?.bottom,
                            left: card.stats?.left === 10 ? 'A' : card.stats?.left
                        }
                    };
                });

                setAllCards(formattedCatalog);
                setProfileData(userData);
                setRelationStatus(userData.friendship_status || 'none');
            }
        } catch (error: any) {
            //console.error("Error al obtener los datos:", error);
            if (isActive) setProfileData(null);
        } finally {
            if (isActive) setIsLoading(false);
        }
    }, [id, authUser?.id, isOwnProfile, i18n.language]);

    useEffect(() => {
        if (isAuthLoading) return;
        let isActive = true;
        fetchData(isActive, !profileData);
        return () => { isActive = false; };
    }, [fetchData, isAuthLoading, profileData === null]);

    const handleAddFriend = async (friendId: number | string) => {
        if (!authUser) return;
        try {
            await userService.sendFriendRequest(authUser.id, friendId);
            setRelationStatus('outgoing');
        } catch (error) {
            //console.error("Error al añadir amigo", error);
        }
    };

    const handleClaimReward = async (achievementId: number) => {
        try {
            const response = await userService.claimAchievement(achievementId);
            if (response) {
                await fetchData(true, false);
            }
        } catch (error) {
            //console.error("Error al reclamar recompensa:", error);
        }
    };

    const getRelativeTime = (dateString: string | null | undefined) => {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);
        const rtf = new Intl.RelativeTimeFormat(i18n.language || 'en', { numeric: 'auto' });

        if (diffInSeconds < 60) return rtf.format(-diffInSeconds, 'second');
        const diffInMinutes = Math.floor(diffInSeconds / 60);
        if (diffInMinutes < 60) return rtf.format(-diffInMinutes, 'minute');
        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) return rtf.format(-diffInHours, 'hour');
        const diffInDays = Math.floor(diffInHours / 24);
        if (diffInDays < 30) return rtf.format(-diffInDays, 'day');
        const diffInMonths = Math.floor(diffInDays / 30);
        if (diffInMonths < 12) return rtf.format(-diffInMonths, 'month');
        const diffInYears = Math.floor(diffInDays / 365);
        return rtf.format(-diffInYears, 'year');
    };

    const formattedHistory = useMemo(() => {
        return profileData?.match_history?.map(match => {
            const isPlayer1 = match.player_1_id === profileData?.id;
            const isWin = match.winner_id === profileData?.id;
            const resultString = match.winner_id === null ? 'draw' : (isWin ? 'win' : 'loss');
            const translatedResult = t(`profile.match_results.${resultString}`);
            const opponentName = isPlayer1 ? match.player_2_name : match.player_1_name;
            const rawAvatar = isPlayer1 ? match.player_2_avatar : match.player_1_avatar;
            const opponentId = isPlayer1 ? match.player_2_id : match.player_1_id;
            const opponentAvatar = rawAvatar === null ? undefined : rawAvatar;
            const scoreFormatted = isPlayer1 ? `${match.p1_score} - ${match.p2_score}` : `${match.p2_score} - ${match.p1_score}`;
            const dateFormatted = getRelativeTime(match.played_at);

            const getMatchStyles = (result: string) => {
                if (result === 'draw') return { border: 'bg-warning', badge: 'bg-warning/10 text-warning border border-warning/20' };
                return result === 'win'
                    ? { border: 'bg-success', badge: 'bg-success/10 text-success border border-success/20' }
                    : { border: 'bg-danger', badge: 'bg-danger/10 text-danger border border-danger/20' };
            };

            return { ...match, resultString, translatedResult, opponentId, opponentName, opponentAvatar, scoreFormatted, dateFormatted, styles: getMatchStyles(resultString) };
        }) || [];
    }, [profileData, t, i18n.language]);

    const totalPages = Math.ceil(formattedHistory.length / ITEMS_PER_PAGE);
    const paginatedHistory = useMemo(() => {
        const start = (currentPage - 1) * ITEMS_PER_PAGE;
        return formattedHistory.slice(start, start + ITEMS_PER_PAGE);
    }, [formattedHistory, currentPage]);

    if (isLoading) return <DashboardLayout isCentered={true}><LoadingState message={t('common.loading')} /></DashboardLayout>;

    return (
        <DashboardLayout isCentered={false}>
            <div className="max-w-5xl mx-auto w-full animate-fade-in-up pb-20 px-4 md:px-0">
                {profileData ? (
                    <>
                        <ProfileHeader
                            userData={{
                                ...profileData,
                                email: profileData.email || "",
                                experience: profileData.stats?.experience || 0,
                                level: profileData.stats?.level || 1,
                                achievement_points: profileData.stats?.achievement_points || 0
                            }}
                            isOwnProfile={isOwnProfile}
                            friendshipStatus={relationStatus}
                            onAddFriend={handleAddFriend}
                        />

                        {/* Stats Section */}
                        <h3 className="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <MdInsertChartOutlined className="text-brand-400 text-2xl" /> {t('profile.stats')}
                        </h3>
                        <div className="grid grid-cols-2 md:grid-cols-5 gap-4 mb-10">
                            {(() => {
                                const wins = profileData.stats?.wins || 0;
                                const losses = profileData.stats?.losses || 0;
                                const draws = profileData.stats?.draws || 0;
                                const gamesPlayed = wins + losses + draws;
                                const winRate = gamesPlayed > 0 ? Math.round((wins / gamesPlayed) * 100) : 0;
                                return (
                                    <>
                                        <StatBox label={t('profile.games_played')} value={gamesPlayed} />
                                        <StatBox label={t('profile.wins')} value={wins} color="text-success" />
                                        <StatBox label={t('profile.draws')} value={draws} color="text-warning" />
                                        <StatBox label={t('profile.losses')} value={losses} color="text-danger" />
                                        <StatBox label={t('profile.win_rate')} value={`${winRate}%`} color="text-brand-400" />
                                    </>
                                );
                            })()}
                        </div>

                        {/* Achievements Section */}
                        <div className="flex justify-between items-center mb-4">
                            <h3 className="text-xl font-bold text-white flex items-center gap-2">
                                <HiOutlineTrophy className="text-warning text-2xl" /> {t('profile.achievements')}
                            </h3>
                            {sortedAchievements.length > 4 && (
                                <button
                                    onClick={() => setShowAllAchievements(!showAllAchievements)}
                                    className="text-xs font-bold text-slate-400 hover:text-white flex items-center gap-1 transition-colors bg-white/5 px-3 py-1.5 rounded-lg border border-white/10"
                                >
                                    {showAllAchievements ? <><FaChevronUp /> {t('common.show_less')}</> : <><FaChevronDown /> {t('common.show_all')}</>}
                                </button>
                            )}
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 relative z-10">
                            {displayedAchievements.map((ach) => (
                                <AchievementCard
                                    key={ach.id}
                                    achievement={ach}
                                    isOwnProfile={isOwnProfile}
                                    rewardCard={ach.card_reward_id ? allCards.find(c => c.id === ach.card_reward_id) : null}
                                    onClaimReward={handleClaimReward}
                                />
                            ))}
                        </div>

                        {/* History Section */}
                        <h3 className="text-xl font-bold text-white mb-4 mt-10 flex items-center gap-2">
                            <MdHistory className="text-brand-400 text-2xl" /> {t('profile.history')}
                        </h3>
                        {formattedHistory.length > 0 ? (
                            <>
                                {/* Mobile List */}
                                <div className="grid gap-4 lg:hidden">
                                    {paginatedHistory.map((match) => (
                                        <div key={match.id} className="glass-panel p-4 relative overflow-hidden">
                                            <div className={`absolute left-0 top-0 bottom-0 w-1 ${match.styles.border}`}></div>
                                            <div className="flex justify-between items-center w-full pl-3 mb-3">
                                                <div className="flex-1"><PlayerBadge avatar={match.opponentAvatar} name={match.opponentName} /></div>
                                                <div className="flex items-center gap-1 text-xs text-slate-500 bg-black/20 px-2 py-1 rounded-md shrink-0 ml-2">{match.dateFormatted}</div>
                                            </div>
                                            <div className="h-px bg-white/5 w-full mb-3 ml-3"></div>
                                            <div className="flex justify-between items-center pl-3">
                                                <span className={`px-3 py-1 rounded text-xs font-black uppercase tracking-widest ${match.styles.badge}`}>{match.translatedResult.toUpperCase()}</span>
                                                <span className="text-2xl font-mono font-bold text-white tracking-widest">{match.scoreFormatted}</span>
                                            </div>
                                        </div>
                                    ))}
                                </div>

                                {/* Desktop Table */}
                                <div className="hidden lg:block glass-panel overflow-hidden">
                                    <table className="w-full text-left text-sm text-slate-400">
                                        <thead className="bg-white/5 text-slate-200 uppercase text-xs font-bold">
                                            <tr className="text-center">
                                                <th className="px-6 py-4 w-8"></th>
                                                <th className="px-6 py-4 w-40">{t('profile.result')}</th>
                                                <th className="px-6 py-4 text-center">{t('profile.opponent')}</th>
                                                <th className="px-6 py-4 w-40">{t('profile.score')}</th>
                                                <th className="px-6 py-4 w-44">{t('profile.date')}</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-white/5">
                                            {paginatedHistory.map((match) => (
                                                <tr key={match.id} className="hover:bg-white/5 transition-colors text-center relative group">
                                                    <td className={`absolute left-0 top-0 bottom-0 w-1 transition-all group-hover:w-1.5 ${match.styles.border}`}></td>
                                                    <td className="px-6 py-4"><span className={`px-2 py-1 rounded text-xs font-bold ${match.styles.badge}`}>{match.translatedResult.toUpperCase()}</span></td>
                                                    <td className="px-6 py-4">
                                                        <Link to={`/profile/${match.opponentId}`} className="flex justify-center hover:opacity-80 transition-opacity">
                                                            <PlayerBadge avatar={match.opponentAvatar} name={match.opponentName} />
                                                        </Link>
                                                    </td>
                                                    <td className="px-6 py-4 font-mono text-white font-bold tracking-widest">{match.scoreFormatted}</td>
                                                    <td className="px-6 py-4">{match.dateFormatted}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>

                                {/* Pagination Controls */}
                                {totalPages > 1 && (
                                    <div className="flex items-center justify-center gap-2 mt-6">
                                        <button
                                            onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
                                            disabled={currentPage === 1}
                                            className="p-2 rounded-lg bg-white/5 border border-white/10 text-white disabled:opacity-20 disabled:cursor-not-allowed hover:bg-brand-500/20 transition-colors"
                                        >
                                            <MdChevronLeft size={24} />
                                        </button>

                                        <div className="flex items-center gap-1">
                                            {[...Array(totalPages)].map((_, i) => (
                                                <button
                                                    key={i}
                                                    onClick={() => setCurrentPage(i + 1)}
                                                    className={`w-10 h-10 rounded-lg border transition-all font-bold text-sm
                                                        ${currentPage === i + 1
                                                            ? 'bg-brand-500 border-brand-400 text-white shadow-lg shadow-brand-500/20'
                                                            : 'bg-white/5 border-white/10 text-slate-400 hover:bg-white/10'}`}
                                                >
                                                    {i + 1}
                                                </button>
                                            ))}
                                        </div>

                                        <button
                                            onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
                                            disabled={currentPage === totalPages}
                                            className="p-2 rounded-lg bg-white/5 border border-white/10 text-white disabled:opacity-20 disabled:cursor-not-allowed hover:bg-brand-500/20 transition-colors"
                                        >
                                            <MdChevronRight size={24} />
                                        </button>
                                    </div>
                                )}
                            </>
                        ) : (
                            <div className="glass-panel p-10 text-center flex flex-col items-center justify-center bg-dark-800/40 border border-white/5 rounded-xl">
                                <FaGamepad className="text-4xl text-slate-600 mb-3" />
                                <span className="text-slate-400 font-bold text-sm">{t('profile.no_history')}</span>
                            </div>
                        )}
                    </>
                ) : (
                    <div className="flex flex-col items-center justify-center py-20 text-center animate-fade-in glass-panel bg-dark-800/40 border border-danger-500/20">
                        <div className="w-16 h-16 bg-danger-500/10 rounded-full flex items-center justify-center mb-4 border border-danger-500/20"><FaExclamationTriangle className="text-3xl text-danger-500" /></div>
                        <h3 className="text-xl font-bold text-white mb-2">{t('profile.error_loading')}</h3>
                        <p className="text-slate-400 max-w-md mb-6">{t('errors.database_error')}</p>
                        <button onClick={() => window.location.reload()} className="btn-secondary px-6 py-2 rounded-lg text-sm font-bold hover:bg-white/10 transition-colors">{t('profile.try_again')}</button>
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
};

export default Profile;