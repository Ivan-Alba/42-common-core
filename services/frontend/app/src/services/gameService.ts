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
        const response = await api.post('/v1/matchmaking/join', {
            game_mode: game_mode,
        });
        return response.data;
    },

    /* Go back to the index and leave the matchmaking queue */
    leaveQueue: async () => {
        const response = await api.post('/v1/matchmaking/cancel');
        return response.data;
    },

    /* Check matchmaking status. If is_ready: true, return match data, else return null */
    checkQueueStatus: async (): Promise<MatchData | null> => {
        const response = await api.get('/v1/matchmaking/status');
        if (response.data && response.data.is_ready) {
            return response.data;
        }
        return null;
    },

    /**
     * Standard abandonment via API (Axios).
     * Used for intentional "Quit" buttons or normal app navigation.
     */
    abandonMatch: async (matchUuid: string) => {
        const response = await api.post(`/v1/match/${matchUuid}/abandon`);
        return response.data;
    },

    /**
     * Emergency abandonment via sendBeacon.
     * Used for tab closing, refreshes, or browser-level exits.
     * Matches the 'forceOffline' formula.
     */
    abandonMatchEmergency: (matchUuid: string) => {
        // Construct the full URL for the beacon
        const url = `/v1/match/${matchUuid}/abandon`;

        const formData = new FormData();
        formData.append('_method', 'POST');
        // If your backend needs the token and doesn't use cookies, 
        // you might need to append it here or as a query param.

        return navigator.sendBeacon(url, formData);
    }
};

export default gameService;