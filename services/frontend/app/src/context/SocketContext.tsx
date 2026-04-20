import React, { createContext, useContext, useEffect, useState } from 'react';
import echo from '../utils/echo'; // Pre-configured Echo instance
import Echo from 'laravel-echo';
import { useAuth } from './AuthContext'; // Hook to listen for auth state changes

const SocketContext = createContext<Echo | null>(null);

export const SocketProvider = ({ children }: { children: React.ReactNode }) => {
    const { isAuthenticated, user } = useAuth();
    const [isReady, setIsReady] = useState(false);

    useEffect(() => {
        // Retrieve tokens stored by authService.getUser() during login/checkSession
        const token = sessionStorage.getItem('unity_auth_token');
        const userId = sessionStorage.getItem('unity_user_id');

        // We only connect if the user is authenticated and we have the necessary Unity tokens
        if (isAuthenticated && user && token && userId) {

            // TOKEN REINFORCEMENT:
            // Ensure the Echo instance uses the latest token from this session.
            // This prevents authorization issues if the user just logged in without a page refresh.
            echo.options.auth.headers.Authorization = `Bearer ${token}`;

            console.log(`[SocketContext] Echo ready for user: ${userId}`);

            // Manually trigger the connection if it was disconnected
            echo.connector.connect();

            setIsReady(true);
        } else {
            // If the user logs out, we should clean up the connection
            if (isReady) {
                console.log("[SocketContext] User logged out. Disconnecting Reverb...");
                echo.disconnect();
                setIsReady(false);
            }
        }

        // Dependency on isAuthenticated and user ensures this runs 
        // immediately after the AuthContext updates its state.
    }, [isAuthenticated, user]);

    return (
        // Provide the echo instance only when the connection is established and ready
        <SocketContext.Provider value={isReady ? echo : null}>
            {children}
        </SocketContext.Provider>
    );
};

/**
 * Custom hook to access the global Echo instance.
 * Returns null if the socket is not connected or the user is not authenticated.
 */
export const useSocket = () => useContext(SocketContext);