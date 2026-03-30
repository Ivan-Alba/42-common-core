import api from './api';
import type { LoginCredentials, RegisterCredentials, User } from '../context/Auth';

const authService = {
    /* Get CSRF token from Laravel Sanctum */
    getCsrfToken: async (): Promise<void> => {
        await api.get('/sanctum/csrf-cookie');
    },

    /* Login */
    login: async (creds: LoginCredentials): Promise<User> => {
        /* Security handshake to get the cookie CSRF */
        await authService.getCsrfToken();
        /* Send credentials to backend, Sanctum will create the session if everithing is ok */
        await api.post('/login', creds);

        /* If login is successful, get the current user to update the authentication state in the frontend */
        return authService.getUser();
    },

    /* Register */
    register: async (data: RegisterCredentials): Promise<User> => {
        await authService.getCsrfToken();
        await api.post('/register', data);
        return authService.getUser();
    },

    /* Emergency Force Offline (when user closed the browser without logging out, force the user offline in the backend) */
    forceOffline: () => {
        const url = `/v1/user/force-offline`;

        const formData = new FormData();
        formData.append('_method', 'POST');

        navigator.sendBeacon(url, formData);
    },

    /* Logout */
    logout: async (): Promise<void> => {
        await api.post('/logout');
        sessionStorage.removeItem('unity_auth_token');
        sessionStorage.removeItem('unity_user_id');
    },

    /** getUser to obtain actual user (Check Session) */
    getUser: async (): Promise<User> => {
        const response = await api.get(`/v1/user`);

        if (response.data && response.data.id) {
            const userId = response.data.id;
            sessionStorage.setItem('unity_user_id', userId);
            //console.log("[Auth] User ID captured and stored in sessionStorage:", userId);
        }

        const unityToken = response.headers['x-unity-token'];
        if (unityToken) {
            sessionStorage.setItem('unity_auth_token', unityToken);
        } else {
            console.warn("[Auth] X-Unity-Token not found in response headers.");
        }

        return response.data;
    }
};

export default authService;