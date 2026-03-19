/* MatchHistory imported to UserProfile to include match history */
import type { MatchHistory } from "./Match";

/* Definition of User-related interfaces */
export interface UserStats {
	level?: number;
    experience?: number;
    gamesPlayed?: number;
    wins?: number;
    losses?: number;
	draws?: number;
    winRate?: number;
}

export interface UserProfile {
	id: number;
	username: string;
	email?: string;
	avatar?: string;
	status?: 'online' | 'offline' | 'playing';
	stats: UserStats;
	bio?: string;
    language?: 'en' | 'es' | 'ca';
	match_history?: MatchHistory[]; 
	// campos adicionales según sea necesario
}