import axios from 'axios';

/* To create instance with URL base from .env */ 
const api = axios.create({
	/* Base URL from environment variable or default to localhost */
    baseURL: import.meta.env.VITE_API_URL,
	/* Accept send/recieve cookies */
    withCredentials: true,
    headers: {
		/* Common headers for all requests */
        'Content-Type': 'application/json',
		/* Required to identify API requests in Laravel */
        'Accept': 'application/json',
    }
});

export default api;