export interface PlayerStats {
    level: number;
    experience: number;
    ranked_points: number;
    wins: number;      // Nota: En tu Resource es 'wins', no 'victories'
    losses: number;
    draws: number;
    campaign: number;
    last_rank_pos?: number | null; // El ranking puede ser null si es un jugador nuevo
}

export interface RankingUser {
    id: number;
    username: string; // Coincide con 'username' => $this->name
    email: string;
    avatar: string | null;
    bio: string | null;
    language: string;
    stats?: PlayerStats; // Opcional porque usas whenLoaded en el backend
}

export interface PodiumCardProps {
    // Usamos 'Required' para las stats porque en el podio sí o sí necesitamos los puntos
    player: RankingUser & { stats: PlayerStats }; 
    place: number;
    isWinner?: boolean;
    delay?: string;
}