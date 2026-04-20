import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { FaGamepad, FaTrophy, FaExclamationTriangle } from "react-icons/fa";
import { MdHistory } from "react-icons/md";
import DashboardLayout from '../components/layouts/DashboardLayout';
import LoadingState from '../components/ui/LoadingState';
import PlayerBadge from '../components/ui/PlayerBadge';
import type { UserProfile } from '../models/User';
import { useAuth } from '../context/AuthContext';
import userService from '../services/userService';
import ProfileHeader from '../components/ui/ProfileHeader';
import StatBox from '../components/ui/StatsBox';

const Profile = () => {
	const { t } = useTranslation();
	const { id } = useParams<{ id: string }>();

	/* Get authenticated user from context */
	const { user: authUser, isLoading: isAuthLoading } = useAuth();

	/* Determine if viewing own profile or another user's */
	/* Check if URL ID matches authenticated user or if no ID is provided */
	const isOwnProfile = Boolean(!id || (authUser && Number(id) === Number(authUser.id)));

	const [profileData, setProfileData] = useState<UserProfile | null>(null);
	const [isLoading, setIsLoading] = useState(true);

	/* Handle to manage friendship status */
	const [relationStatus, setRelationStatus] = useState<'none' | 'pending' | 'accepted' | 'outgoing'>('none');

	const getMatchStyles = (result: 'win' | 'loss' | 'draw') => {
		if (result === 'draw') {
            return {
                border: 'bg-warning',
                badge: 'bg-warning/10 text-warning border border-warning/20'
            };
        }
		const isWin = result === 'win';
		return {
			border: isWin ? 'bg-success' : 'bg-danger',
			badge: isWin
				? 'bg-success/10 text-success border border-success/20'
				: 'bg-danger/10 text-danger border border-danger/20'
		};
	};

	useEffect(() => {
		if (isAuthLoading) return;

		const fetchProfileData = async () => {
			setIsLoading(true);
			try {
				/* If viewing own profile, use authenticated user's ID; otherwise, use the ID from the URL */
				const targetId = isOwnProfile ? authUser?.id : id;
				if (!targetId) throw new Error("No user specified");

				const data = await userService.getProfile(targetId);
				setProfileData(data);

				// IMPORTANTE: Cuando el backend esté listo y devuelva el status de la amistad en el profile ajeno, lo seteamos aquí
				// setRelationStatus(data.friendship_status || 'none');

			} catch (error: any) {
				console.error("Error al obtener los datos de la base de datos:", error);
				setProfileData(null);
			} finally {
				setIsLoading(false);
			}
		};

		fetchProfileData();
	}, [id, authUser, isAuthLoading, isOwnProfile]);

		/* Handle to manage adding friend */
	const handleAddFriend = async (friendId: number | string) => {
		if (!authUser) return;
		try {
			await userService.sendFriendRequest(authUser.id, friendId);
			console.log(`Solicitud enviada desde el perfil al ID ${friendId}`);

			setRelationStatus('outgoing');

		} catch (error) {
			console.error("Error enviando solicitud", error);
			setRelationStatus('outgoing');
		}
	};

	if (isLoading)
		return <DashboardLayout isCentered={true}><LoadingState message={t('common.loading')} /></DashboardLayout>;

	/* Format match history data */
	const formattedHistory = profileData?.match_history?.map(match => {
        const isPlayer1 = match.player_1_id === profileData?.id;
        const isWin = match.winner_id === profileData?.id;
		const resultString = match.winner_id === null ? 'draw' : (isWin ? 'win' : 'loss');
		const translatedResult = t(`profile.match_results.${resultString}`);
        const opponentName = isPlayer1 ? match.player_2_name : match.player_1_name;
        const rawAvatar = isPlayer1 ? match.player_2_avatar : match.player_1_avatar;
        const opponentAvatar = rawAvatar === null ? undefined : rawAvatar;
        const scoreFormatted = isPlayer1 ? `${match.p1_score} - ${match.p2_score}` : `${match.p2_score} - ${match.p1_score}`;
        const dateFormatted = match.played_at ? match.played_at.split(' ')[0] : 'N/A';
        const styles = getMatchStyles(resultString as 'win' | 'loss' | 'draw');

        /* Complete object with formatted data */
        return {
            ...match,
            resultString,
			translatedResult,
            opponentName,
            opponentAvatar,
            scoreFormatted,
            dateFormatted,
            styles
        };
    }) || [];

	return (
		<DashboardLayout isCentered={false}>
			<div className="max-w-5xl mx-auto w-full animate-fade-in-up pb-20">
				{profileData ? (
					<>
						{/* HEADER */}
						<ProfileHeader
							userData={{
								...profileData,
								email: profileData.email || "",
								experience: profileData.stats?.experience || 0,
								level: profileData.stats?.level || 1,
							}}
							isOwnProfile={true}
							friendshipStatus={relationStatus}
							onAddFriend={handleAddFriend}
						/>

						{/* STATS */}
						<h3 className="text-xl font-bold text-white mb-4 flex items-center gap-2"><FaTrophy className="text-warning" /> {t('profile.stats')}</h3>
						<div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
							{(() => {
								const wins = profileData.stats?.wins || 0;
								const losses = profileData.stats?.losses || 0;
								const draws = profileData.stats?.draws || 0;

								/* Calculate total games played */
								const gamesPlayed = profileData.stats?.gamesPlayed || (wins + losses + draws);

								/* Calculate win rate percentage (avoid division by zero) */
								const winRate = profileData.stats?.winRate || (gamesPlayed > 0 ? Math.round((wins / gamesPlayed) * 100) : 0);

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

						{/* HISTORY */}
						<h3 className="text-xl font-bold text-white mb-4 flex items-center gap-2"><MdHistory className="text-brand-400" /> {t('profile.history')}</h3>

                        {formattedHistory.length > 0 ? (
                            <>
                                {/* --- MOBILE VIEW --- */}
                                <div className="grid gap-4 lg:hidden">
                                    {formattedHistory.map((match) => (
                                        <div key={match.id} className="glass-panel p-4 relative overflow-hidden">
                                            <div className={`absolute left-0 top-0 bottom-0 w-1 ${match.styles.border}`}></div>
                                            <div className="flex justify-between items-center w-full pl-3 mb-3">
                                                <div className="flex-1">
                                                    <PlayerBadge
                                                        avatar={match.opponentAvatar}
                                                        name={match.opponentName}
                                                        className="justify-start! [&>div:first-child]:w-auto [&>div:first-child]:pr-3 [&>div:last-child]:w-auto [&>div:last-child]:pl-3"
                                                    />
                                                </div>
                                                <div className="flex items-center gap-1 text-xs text-slate-500 bg-black/20 px-2 py-1 rounded-md shrink-0 ml-2">
                                                    {match.dateFormatted}
                                                </div>
                                            </div>
                                            <div className="h-px bg-white/5 w-full mb-3 ml-3"></div>
                                            <div className="flex justify-between items-center pl-3">
                                                <span className={`px-3 py-1 rounded text-xs font-black uppercase tracking-widest ${match.styles.badge}`}>
                                                    {match.translatedResult.toUpperCase()}
                                                </span>
                                                <span className="text-2xl font-mono font-bold text-white tracking-widest">
                                                    {match.scoreFormatted}
                                                </span>
                                            </div>
                                        </div>
                                    ))}
                                </div>

                                {/* DESKTOP TABLE */}
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
                                                    <td className="px-6 py-4">
                                                        <span className={`px-2 py-1 rounded text-xs font-bold ${match.styles.badge}`}>
                                                            {match.translatedResult.toUpperCase()}
                                                        </span>
                                                    </td>
                                                    <td className="px-6 py-4">
                                                        <div className="flex justify-center">
                                                            <PlayerBadge avatar={match.opponentAvatar} name={match.opponentName} />
                                                        </div>
                                                    </td>
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