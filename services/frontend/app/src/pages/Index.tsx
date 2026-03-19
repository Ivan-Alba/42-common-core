import { useTranslation } from 'react-i18next';
import { FaUserFriends, FaTrophy, FaImages } from "react-icons/fa";
import { GiCardPlay } from "react-icons/gi";
import DashboardLayout from '../components/layouts/DashboardLayout';
import DashboardCard from '../components/ui/DashboardCard';
import { useNavigate } from 'react-router-dom';
import { useEffect, useState } from 'react';
import type { UserProfile } from '../models/User';
import { useAuth } from '../context/AuthContext';
import LoadingState from '../components/ui/LoadingState';
import ProfileHeader from '../components/ui/ProfileHeader';
import GameModeModal from '../components/ui/GameModeModal';
import userService from '../services/userService';

//ivan
import GameLauncher from '../components/GameLauncher';

const Index = () => {
	const { t } = useTranslation();
	const navigate = useNavigate();
	const { user: authUser, isLoading: isAuthLoading } = useAuth();

	/* States to save friend list, profile data and mode selector visibility */
	const [profileData, setProfileData] = useState<UserProfile | null>(null);
	const [friendsList, setFriendsList] = useState<UserProfile[]>([]);
	const [showModeSelector, setShowModeSelector] = useState(false);
	const [isLoading, setIsLoading] = useState(true);

	/* Fetch user profile data on mount and when authUser changes */
	useEffect(() => {
		const fetchCurrentUserInfo = async () => {
			if (!authUser?.id) return;

			try {
				// Pedimos los datos frescos a la BBDD (incluyendo el nuevo avatar en /media/)
				const data = await userService.getProfile(authUser.id);
				setProfileData(data);
			} catch (error) {
				console.error("Error actualizando datos de usuario en Index:", error);
			} finally {
				setIsLoading(false);
			}
		};

		fetchCurrentUserInfo();
	}, [authUser?.id]);

	/* Real fetch to get friends from database */
	useEffect(() => {
		const fetchFriends = async () => {
			if (!authUser?.id)
				return;
			try {
				const response = await userService.getFriends(authUser.id);

				const friendList = response.filter((f: any) =>
					f.pivot?.status === 'accepted'
				);

				setFriendsList(friendList);
			} catch (error) {
				console.error("Error:", error);
				setFriendsList([]);
			}
		};
		fetchFriends();
	}, [authUser?.id]);

	if (isAuthLoading || isLoading)
		return <LoadingState />;

	if (!authUser || !profileData)
		return null;
	
	return (
		<DashboardLayout isCentered={false}>
			<div className="max-w-5xl mx-auto w-full animate-fade-in-up pb-10">

				{/* Header with user info */}
				<ProfileHeader
					userData={{
						...profileData,
						email: profileData.email || "",
						experience: profileData.stats?.experience || 0,
                        level: profileData.stats?.level || 1,
					}}
					isOwnProfile={true}
				/>

				<GameLauncher />

				{/* Grid Container */}
				<div className="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 animate-fade-in-up">

					{/* Play Card (Primary) - Open Modal */}
					<DashboardCard
						title={t('dashboard.play')}
						subtitle={t('dashboard.select_mode')}
						icon={<GiCardPlay />}
						variant="primary"
						onClick={() => setShowModeSelector(true)}
					/>

					{/* Friends Card */}
					<DashboardCard
						title={t('dashboard.friends') + ` (${friendsList.length})`}
						subtitle={`Online: ${friendsList.filter(f => f.status === 'online').length + friendsList.filter(f => f.status === 'playing').length}`}
						icon={<FaUserFriends />}
						onClick={() => navigate("/friends")}
					/>

					{/* Ranking Card */}
					<DashboardCard
						title={t('dashboard.ranking')}
						subtitle={t('dashboard.ranking_info')}
						icon={<FaTrophy />}
						onClick={() => navigate("/ranking")}
					/>

					{/* Collection Card */}
					<DashboardCard
						title={t('dashboard.collection')}
						subtitle={t('dashboard.collection_info')}
						icon={<FaImages />}
						onClick={() => navigate("/collection")}
					/>
				</div>

			</div>

			{/* Game Mode Modal */}
			<GameModeModal
				isOpen={showModeSelector}
				onClose={() => setShowModeSelector(false)}
			/>

		</DashboardLayout>
	);
};

export default Index;