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
import { useCountdown } from '../utils/useCountdown';

const Index = () => {
    const { t } = useTranslation();
    const navigate = useNavigate();
    const { user: authUser, isLoading: isAuthLoading } = useAuth();

    /* States to save friend list, profile data and mode selector visibility */
    const [profileData, setProfileData] = useState<UserProfile | null>(null);
    const [friendsList, setFriendsList] = useState<UserProfile[]>([]);
    const [showModeSelector, setShowModeSelector] = useState(false);
    const [isLoading, setIsLoading] = useState(true);
    const [penaltyTargetDate, setPenaltyTargetDate] = useState<string | null>(null);

    /* Fetch user profile data on mount and when authUser changes */
    useEffect(() => {
        const fetchCurrentUserInfo = async () => {
            if (!authUser?.id) return;

            try {
				/* Asking for fresh data from the DB (including the new avatar in /media/) */
                const data = await userService.getProfile(authUser.id);
                setProfileData(data);
            } catch (error) {
                console.error("Error: ", error);
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

    /* Event Listener to handle friend status changes (Reverb)*/
    useEffect(() => {
        const handleStatusChange = (e: any) => {
            const { userId, newStatus } = e.detail;

            /* Update friends list when status changes */
            setFriendsList((prevFriends) => 
                prevFriends.map((friend) => 
                    Number(friend.id) === Number(userId) 
                        ? { ...friend, status: newStatus } 
                        : friend
                )
            );
        };

        window.addEventListener('friendStatusChanged', handleStatusChange);
        
        return () => {
            window.removeEventListener('friendStatusChanged', handleStatusChange);
        };
    }, []);

	/* Calculate penalty target date based on remaining seconds */
    useEffect(() => {
        if (profileData?.penalty_remaining_seconds && profileData.penalty_remaining_seconds > 0) {
            // Fecha actual + (segundos de penalización * 1000 milisegundos)
            const targetTime = new Date(Date.now() + profileData.penalty_remaining_seconds * 1000);
            setPenaltyTargetDate(targetTime.toISOString());
        } else {
            setPenaltyTargetDate(null);
        }
    }, [profileData?.penalty_remaining_seconds]);

	/* Send penalty target date to countdown hook to get time left and if it's finished */
    const { timeLeft, isFinished } = useCountdown(penaltyTargetDate);

	/* If there's time left and the penalty isn't finished, the user is penalized */
    const isPenalized = timeLeft !== null && !isFinished;

    /* Calculate online friends count */
    const onlineFriendsCount = friendsList.filter(f => f.status === 'online' || f.status === 'playing').length;

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
                        achievement_points: profileData.stats?.achievement_points || 0,
                        
                    }}
                    isOwnProfile={true}
                />

                <GameLauncher />

                {/* Grid Container */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 animate-fade-in-up">

                    {/* Play Card (Primary) - Open Modal */}
                    <DashboardCard
                        /* If penalized, if not PlayNow" */
                        title={isPenalized ? t('game.penalized') : t('dashboard.play')}
                        /* If penalized, show time left, otherwise show select mode text */
                        subtitle={isPenalized ? t('game.time_remaining') + (timeLeft || "") : t('dashboard.select_mode')}
                        icon={<GiCardPlay />}
                        variant="primary"
                        onClick={() => setShowModeSelector(true)}
                        disabled={isPenalized} 
                    />

                    {/* Friends Card */}
                    <DashboardCard
                        title={t('dashboard.friends') + ` (${friendsList.length})`}
                        subtitle={`Online: ${onlineFriendsCount}`}
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