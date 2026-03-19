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
	/* Game modes: 'campaign' for PVE, 'ranked' for competitive PVP, 'casual' for non-ranked PVP */
    joinQueue: async (game_mode: string) => {
        const response = await api.post('/v1/matchmaking/join', { game_mode });
        return response.data;
    },

	/* Go back to the index and leave the matchmaking queue */
    leaveQueue: async () => {
        const response = await api.post('/v1/matchmaking/leave');
        return response.data;
    },

	/* Check matchmaking status. If is_ready: true, return match data, else return null */
    checkQueueStatus: async (): Promise<MatchData | null> => {
        const response = await api.get('/v1/matchmaking/status');
		/* If the backend returns is_ready: true, we return the data. Otherwise, we return null to indicate that we're still waiting. This way, the frontend can easily check if a match is ready without having to handle exceptions for flow control. */
        if (response.data && response.data.is_ready) {
            return response.data;
        }
        return null;
    }
};

export default gameService;