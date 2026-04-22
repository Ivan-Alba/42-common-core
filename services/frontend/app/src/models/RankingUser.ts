// export interface PlayerStats {
//     level: number;
//     experience: number;
//     ranked_points: number;
//     wins: number;
//     losses: number;
//     draws: number;
//     campaign: number;
//     last_rank_pos?: number | null;
// }

// export interface RankingUser {
//     id: number;
//     username: string;
//     email: string;
//     avatar: string | null;
//     bio: string | null;
//     language: string;
//     stats?: PlayerStats;
// }

// export interface PodiumCardProps {
//     player: RankingUser & { stats: PlayerStats }; 
//     place: number;
//     isWinner?: boolean;
//     delay?: string;
// }

import type { UserProfile } from './User';

// Solo dejamos las props del componente, usando nuestro modelo unificado
export interface PodiumCardProps {
    player: UserProfile; 
    place: number;
    isWinner?: boolean;
    delay?: string;
}