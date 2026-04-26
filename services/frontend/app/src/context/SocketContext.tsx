import React, { createContext, useContext, useEffect, useState } from 'react';
import echo from '../utils/echo';
import Echo from 'laravel-echo';
import { useAuth } from './AuthContext';

const SocketContext = createContext<Echo<any> | null>(null);

export const SocketProvider = ({ children }: { children: React.ReactNode }) => {
    const { isAuthenticated, user } = useAuth();
    const [isReady, setIsReady] = useState(false);

    useEffect(() => {
        const token = sessionStorage.getItem('unity_auth_token');
        const userId = sessionStorage.getItem('unity_user_id');

        // Referencia al motor de conexión para acortar el código
        const connector = echo.connector.pusher;

        if (isAuthenticated && user && token && userId) {
            echo.options.auth = {
                ...echo.options.auth,
                headers: {
                    ...(echo.options.auth?.headers || {}),
                    Authorization: `Bearer ${token}`
                }
            };

            // Intentamos conectar solo si está desconectado
            if (connector.connection.state === 'disconnected') {
                echo.connector.connect();
            }

            const channel = echo.private(`user.${userId}`);

            channel.listen('.UserStatusChanged', (data: { userId: number, newStatus: string }) => {
                window.dispatchEvent(new CustomEvent('friendStatusChanged', {
                    detail: { userId: data.userId, newStatus: data.newStatus }
                }));
            });

            channel.listen('.FriendRequestReceived', (_data: any) => {
                window.dispatchEvent(new CustomEvent('friendRequestReceived'));
                window.dispatchEvent(new Event('updateFriendNotifications'));
            });

            channel.listen('.FriendRequestAccepted', (_data: any) => {
                window.dispatchEvent(new Event('updateFriendNotifications'));
            });

            setIsReady(true);
        }

        return () => {
            const state = connector.connection.state;
            
            /* LA CLAVE: Solo intentamos dejar canales o desconectar si 
               la conexión está totalmente ESTABLECIDA ('connected').
               Si está en 'connecting', no la tocamos para evitar el error de "closed before established".
            */
            if (state === 'connected') {
                if (userId) {
                    echo.leave(`user.${userId}`);
                }

                if (!isAuthenticated) {
                    console.log("[SocketContext] Desconectando Echo de forma segura...");
                    echo.disconnect();
                    setIsReady(false);
                }
            } else if (state === 'connecting' && !isAuthenticated) {
                // Si estamos conectando pero el usuario se ha ido (logout), 
                // esperamos un pelín para desconectar o simplemente dejamos que el timeout limpie.
                // Pero lo más seguro es no forzar el disconnect aquí para evitar el warning.
                setTimeout(() => {
                    if (connector.connection.state === 'connected' && !isAuthenticated) {
                        echo.disconnect();
                    }
                }, 500);
            }
        };

    }, [isAuthenticated, user]);
    
    return (
        <SocketContext.Provider value={isReady ? echo : null}>
            {children}
        </SocketContext.Provider>
    );
};

export const useSocket = () => useContext(SocketContext);