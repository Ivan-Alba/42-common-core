import api from './api';

export interface MatchData {
    match_id: number;
    opponent: {
        id: number;
        username: string;
        avatar?: string;
    };
    is_ready: boolean;
}

const gameService = {
    // 1. Entrar a la cola
    joinQueue: async (mode: string, submode?: string | null) => {
        const response = await api.post('/v1/matchmaking/join', { mode, submode });
        return response.data;
    },

    // 2. Salir de la cola (si el usuario le da a cancelar)
    leaveQueue: async () => {
        const response = await api.post('/v1/matchmaking/leave');
        return response.data;
    },

    // 3. Comprobar si ya hay partida (Polling)
    checkQueueStatus: async (): Promise<MatchData | null> => {
        const response = await api.get('/v1/matchmaking/status');
        // Si el backend devuelve is_ready: true, devolvemos los datos
        if (response.data && response.data.is_ready) {
            return response.data;
        }
        return null;
    }
};

export default gameService;