// /* MatchHistory imported to UserProfile to include match history */
// import type { MatchHistory } from "./Match";

// /* Definition of User-related interfaces */

// export interface UserStats {
// 	level?: number;
//     experience?: number;
//     gamesPlayed?: number;
//     wins?: number;
//     losses?: number;
// 	draws?: number;
//     winRate?: number;
// 	achievement_points?: number;
// 	campaign?: number;
//     ranked_points?: number;
//     last_rank_pos?: number | null;
// }

// export interface UserProfile {
// 	id: number;
// 	username: string;
// 	email?: string;
// 	avatar?: string;
// 	status?: 'online' | 'offline' | 'playing' | 'away';
// 	stats: UserStats;
// 	bio?: string;
//     language?: 'en' | 'es' | 'ca';
// 	match_history?: MatchHistory[];
// 	friendship_status?: 'none' | 'pending' | 'accepted' | 'outgoing' | 'rejected';
// }

/* MatchHistory imported to UserProfile to include match history */
import type { MatchHistory } from "./Match";

/* Definition of User-related interfaces */

export interface Achievement {
    id: number;
    code: string;
    category: 'bronze' | 'silver' | 'gold' | 'diamond' | string;
    title: string;
    description: string;
    goal: number;
    points_reward: number;
    current_progress: number;
    unlocked_at: string | null;
    is_unlocked: boolean;
    claimed: boolean;
    card_reward_id: number | null;
}

export interface UserStats {
    level?: number;
    experience?: number;
    gamesPlayed?: number;
    wins?: number;
    losses?: number;
    draws?: number;
    winRate?: number;
    achievement_points?: number;
    campaign?: number;
    ranked_points?: number;
    last_rank_pos?: number | null;
}

export interface UserProfile {
    id: number;
    username: string;
    email?: string;
    avatar?: string | null;
    status?: 'online' | 'offline' | 'playing' | 'away';
    stats: UserStats;
    bio?: string | null;
    language?: 'en' | 'es' | 'ca' | string;
    match_history?: MatchHistory[];
    friendship_status?: 'none' | 'pending' | 'accepted' | 'outgoing' | 'rejected';
	achievements?: Achievement[];
	penalty_remaining_seconds?: number | null;
}