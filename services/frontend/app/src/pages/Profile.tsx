import { useEffect, useState, useMemo } from 'react';
import { useParams, Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import {
	FaGamepad, FaExclamationTriangle,
	FaLock, FaCheckCircle, FaGift, FaChevronDown, FaChevronUp
} from "react-icons/fa";
import { MdHistory, MdInsertChartOutlined } from "react-icons/md";
import { HiOutlineTrophy } from "react-icons/hi2";
import DashboardLayout from '../components/layouts/DashboardLayout';
import LoadingState from '../components/ui/LoadingState';
import PlayerBadge from '../components/ui/PlayerBadge';
import type { UserProfile } from '../models/User';
import { useAuth } from '../context/AuthContext';
import userService from '../services/userService';
import ProfileHeader from '../components/ui/ProfileHeader';
import StatBox from '../components/ui/StatsBox';
import i18n from '../i18n';

const Profile = () => {
	const { t } = useTranslation();
	const { id } = useParams<{ id: string }>();

	const { user: authUser, isLoading: isAuthLoading } = useAuth();
	const isOwnProfile = Boolean(!id || (authUser && Number(id) === Number(authUser.id)));

	const [profileData, setProfileData] = useState<UserProfile | null>(null);
	const [isLoading, setIsLoading] = useState(true);
	const [showAllAchievements, setShowAllAchievements] = useState(false);
	const [relationStatus, setRelationStatus] = useState<'none' | 'pending' | 'accepted' | 'outgoing' | 'rejected'>('none');

	// Ordenación inteligente: 1. Desbloqueados por reclamar, 2. Ya reclamados, 3. Bloqueados
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

	const getMatchStyles = (result: 'win' | 'loss' | 'draw') => {
		if (result === 'draw') return { border: 'bg-warning', badge: 'bg-warning/10 text-warning border border-warning/20' };
		const isWin = result === 'win';
		return {
			border: isWin ? 'bg-success' : 'bg-danger',
			badge: isWin ? 'bg-success/10 text-success border border-success/20' : 'bg-danger/10 text-danger border border-danger/20'
		};
	};

	const getCategoryStyles = (category: string, isUnlocked: boolean, isClaimed: boolean) => {
		if (!isUnlocked) return "text-slate-500 border-white/5 bg-white/5 opacity-60 grayscale-[0.5]";

		const unlockedBg = "bg-slate-500/40 backdrop-blur-sm";

		if (isUnlocked && !isClaimed) {
			return `text-white border-brand-500 ${unlockedBg} shadow-[0_0_15px_rgba(var(--brand-rgb),0.2)] animate-pulse-subtle`;
		}

		switch (category) {
			case 'bronze':
				return `text-orange-400 border-orange-700/50 ${unlockedBg}`;
			case 'silver':
				return `text-slate-200 border-slate-500/50 ${unlockedBg}`;
			case 'gold':
				return `text-yellow-400 border-yellow-600/50 ${unlockedBg} shadow-[0_0_10px_rgba(202,138,4,0.1)]`;
			case 'diamond':
				return `text-cyan-400 border-cyan-500/50 ${unlockedBg} shadow-[0_0_15px_rgba(34,211,238,0.2)]`;
			default:
				return `text-brand-400 border-brand-900/50 ${unlockedBg}`;
		}
	};

	useEffect(() => {
		if (isAuthLoading) return;
		const fetchProfileData = async () => {
			setIsLoading(true);
			try {
				const targetId = isOwnProfile ? authUser?.id : id;
				if (!targetId) throw new Error("No user specified");
				const data = await userService.getProfile(targetId);
				setProfileData(data);
				setRelationStatus(data.friendship_status || 'none');
			} catch (error: any) {
				console.error("Error al obtener los datos:", error);
				setProfileData(null);
			} finally {
				setIsLoading(false);
			}
		};
		fetchProfileData();
	}, [id, authUser, isAuthLoading, isOwnProfile]);

	const handleAddFriend = async (friendId: number | string) => {
		if (!authUser) return;
		try {
			await userService.sendFriendRequest(authUser.id, friendId);
			setRelationStatus('outgoing');
		} catch (error) {
			console.error("Error", error);
		}
	};

	const handleClaimReward = async (achievementId: number) => {
		try {
			/* Call to the claimAchievement method of the userService */
			const response = await userService.claimAchievement(achievementId);

			if (response) {
				/* Inmutable update of state to reflect the change visually */
				setProfileData(prev => {
					if (!prev) return prev;

					const updatedAchievements = (prev.achievements || []).map(ach => {
						if (ach.id === achievementId) {
							return { ...ach, claimed: true };
						}
						return ach;
					});

					/* Update stats if backend returns new total points */
					const updatedStats = response.new_total_points
						? { ...prev.stats, achievement_points: response.new_total_points }
						: prev.stats;

					return { ...prev, achievements: updatedAchievements, stats: updatedStats };
				});
			}
		} catch (error) {
			console.error("Error:", error);
		}
	};

	if (isLoading) return <DashboardLayout isCentered={true}><LoadingState message={t('common.loading')} /></DashboardLayout>;

	/* Function to calculate "how long ago" */
	const getRelativeTime = (dateString: string | null | undefined) => {
		if (!dateString)
			return 'N/A';

		/* Convert the date to milliseconds and calculate the difference */
		const date = new Date(dateString);
		const now = new Date();
		const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);

		/* Instantiates Intl.RelativeTimeFormat tied to the current i18n locale.
		This utilizes the browser's native Unicode CLDR engine for zero-dependency translations.
		The { numeric: 'auto' } parameter prioritizes semantic strings ("tomorrow", "yesterday") 
		over purely numeric representations. */
		const rtf = new Intl.RelativeTimeFormat(i18n.language || 'en', { numeric: 'auto' });

		/* We calculate the best unit to show */
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

	const formattedHistory = profileData?.match_history?.map(match => {
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
		const styles = getMatchStyles(resultString as 'win' | 'loss' | 'draw');

		return { ...match, resultString, translatedResult, opponentId, opponentName, opponentAvatar, scoreFormatted, dateFormatted, styles };
	}) || [];

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
						<div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
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

						<div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
							{displayedAchievements.map((ach) => {
								const progressPercent = Math.min(Math.round((ach.current_progress / ach.goal) * 100), 100);
								const styles = getCategoryStyles(ach.category, ach.is_unlocked, ach.claimed);
								const showProgressBar = !ach.is_unlocked && ach.goal > 1;
								const canClaim = ach.is_unlocked && !ach.claimed;

								return (
									<div key={ach.id} className={`glass-panel p-4 flex gap-4 border transition-all duration-300 ${styles}`}>
										<div className="shrink-0 flex flex-col items-center justify-center w-12">0
											{ach.is_unlocked ? (
												<FaCheckCircle className={`text-3xl animate-fade-in ${ach.claimed ? 'text-success' : 'text-brand-400'}`} />
											) : (
												<FaLock className="text-2xl text-slate-600" />
											)}
											<span className="text-[10px] font-black uppercase mt-2 tracking-tighter opacity-70">
												{ach.category}
											</span>
										</div>

										<div className="flex-1">
											<div className="flex justify-between items-start mb-1">
												<h4 className={`font-bold leading-tight ${ach.is_unlocked ? 'text-white' : 'text-slate-400'}`}>
													{ach.title}
												</h4>
												<div className="flex flex-col items-end gap-1">
													{ach.card_reward_id && (
														<div className="text-brand-400 flex items-center gap-1 shrink-0">
															<FaGift className="text-xs" />
															<span className="text-[10px] font-bold">CARD</span>
														</div>
													)}
													<span className="text-[10px] text-warning font-bold">+{ach.points_reward} PTS</span>
												</div>
											</div>
											<p className="text-xs text-slate-500 mb-3 italic leading-relaxed">{ach.description}</p>

											{canClaim ? (
												isOwnProfile && (
													<button
														onClick={() => handleClaimReward(ach.id)}
														className="w-full bg-brand-500 hover:bg-brand-600 text-white text-[10px] font-black py-2 rounded shadow-lg shadow-brand-500/20 transition-all transform hover:scale-[1.02] active:scale-[0.98] animate-pulse"
													>
														{t('profile.claim_reward').toUpperCase()}
													</button>
												)
											) : (
												<>
													{showProgressBar && (
														<div className="space-y-1">
															<div className="flex justify-between text-[10px] font-bold">
																<span className="text-slate-500 uppercase">{t('profile.progress')}</span>
																<span className="text-slate-400">{ach.current_progress} / {ach.goal}</span>
															</div>
															<div className="w-full bg-black/40 rounded-full h-1.5 border border-white/5 overflow-hidden">
																<div className="bg-brand-500 h-full transition-all duration-1000 ease-out" style={{ width: `${progressPercent}%` }}></div>
															</div>
														</div>
													)}
													{ach.is_unlocked && ach.claimed && ach.unlocked_at && (
														<div className="text-[10px] text-success/60 font-mono italic">
															{t('profile.unlocked_on')} {ach.unlocked_at.split(' ')[0]}
														</div>
													)}
												</>
											)}
										</div>
									</div>
								);
							})}
						</div>

						{/* Historial */}
						<h3 className="text-xl font-bold text-white mb-4 mt-10 flex items-center gap-2">
							<MdHistory className="text-brand-400 text-2xl" /> {t('profile.history')}
						</h3>
						{formattedHistory.length > 0 ? (
							<>
								<div className="grid gap-4 lg:hidden">
									{formattedHistory.map((match) => (
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
											{formattedHistory.map((match) => (
												<tr key={match.id} className="hover:bg-white/5 transition-colors text-center relative group">
													<td className={`absolute left-0 top-0 bottom-0 w-1 transition-all group-hover:w-1.5 ${match.styles.border}`}></td>
													<td className="px-6 py-4"><span className={`px-2 py-1 rounded text-xs font-bold ${match.styles.badge}`}>{match.translatedResult.toUpperCase()}</span></td>
													<Link to={`/profile/${match.opponentId}`}>
														<td className="px-6 py-4"><div className="flex justify-center"><PlayerBadge avatar={match.opponentAvatar} name={match.opponentName} /></div></td>
													</Link>
													<td className="px-6 py-4 font-mono text-white font-bold tracking-widest">{match.scoreFormatted}</td>
													<td className="px-6 py-4">{match.dateFormatted}</td>
												</tr>
											))}
										</tbody>
									</table>
								</div>
							</>
						) : (
							<div className="glass-panel p-10 text-center flex flex-col items-center justify-center">
								<FaGamepad className="text-4xl text-slate-600 mb-3" />
								<span className="text-slate-400 font-bold">{t('profile.no_history')}</span>
							</div>
						)}
					</>
				) : (
					<div className="flex flex-col items-center justify-center py-20 text-center animate-fade-in">
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