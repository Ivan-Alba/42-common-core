/* Definition of Match-related interfaces */

export interface MatchHistory {
    id: number;
    player_1_id: number;
    player_2_id: number;
    player_1_name: string;
    player_2_name: string;
    player_1_avatar?: string | null;
    player_2_avatar?: string | null;
    p1_score: number;
    p2_score: number;
    winner_id: number | null;
    played_at: string | null;
    game_mode?: string;
    is_vs_ai?: boolean | number;
}