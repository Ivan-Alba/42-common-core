import type { UserProfile } from './User';

/* PlayerStats propierties should match the data structure returned by the backend for player stats in the ranking endpoint. */
export interface PlayerStats {
    level: number;
    experience: number;
    wins: number;
    losses: number;
    draws: number;
    winRate: number;
    ranked_points: number;
}

/* RankingUser extends UserProfile with the additional stats needed for the ranking page. */
export interface RankingUser {
    id: number;
    username: string;
    avatar?: string | null;
}

/* PodiumCardProps defines the props for a component that displays a player's ranking position on the podium. */
export interface PodiumCardProps {
    player: UserProfile; 
    place: number;
    isWinner?: boolean;
    delay?: string;
}