import React, { createContext, useContext, useEffect, useState } from 'react';
import echo from '../utils/echo';
import Echo from 'laravel-echo';
import { useAuth } from './AuthContext';

const SocketContext = createContext<Echo<any> | null>(null);

export const SocketProvider = ({ children }: { children: React.ReactNode }) => {
	const { isAuthenticated, user } = useAuth();
	const [isReady, setIsReady] = useState(false);

	useEffect(() => {
		/* Retrieve tokens stored by authService.getUser() during login/checkSession */
		const token = sessionStorage.getItem('unity_auth_token');
		const userId = sessionStorage.getItem('unity_user_id');

		/* We only connect if the user is authenticated and we have the necessary Unity tokens */
		if (isAuthenticated && user && token && userId) {

			/* Ensure the Echo instance uses the latest token from this session. */
			echo.options.auth = {
				...echo.options.auth,
				headers: {
					...(echo.options.auth?.headers || {}),
					Authorization: `Bearer ${token}`
				}
			};

			console.log(`[SocketContext] Echo ready for user: ${userId}`);

			/* Manually trigger the connection if it was disconnected */
			echo.connector.connect();

			/* Implementation of the friend status listener:We subscribe to OUR own channel (because friends notify us there) */
			const channel = echo.private(`user.${userId}`);

			/* Listen for friend status changes (online/offline) */
			channel.listen('.UserStatusChanged', (data: { userId: number, newStatus: string }) => {
				console.log(`Reverb: El amigo ${data.userId} ha cambiado a ${data.newStatus}`);

				/* Throw event to the entire React window */
				window.dispatchEvent(new CustomEvent('friendStatusChanged', {
					detail: { userId: data.userId, newStatus: data.newStatus }
				}));
			});

			/* Listen for incoming friend requests */
			channel.listen('.FriendRequestReceived', (_data: any) => {
				console.log(`Reverb: ¡Petición de amistad recibida!`);
				
				/* Throw event to the entire React window to show red badge notification */
				window.dispatchEvent(new CustomEvent('friendRequestReceived'));
				window.dispatchEvent(new Event('updateFriendNotifications'));
			});

			/* Listen for friend request accepted events */
			channel.listen('.FriendRequestAccepted', (_data: any) => {
				console.log(`Reverb: ¡Alguien aceptó tu petición de amistad!`);
				
				/* Throw event to the entire React window to show red badge notification */
				window.dispatchEvent(new Event('updateFriendNotifications'));
			});

			setIsReady(true);

			/* Cleanup: If the component unmounts or deps change */
			return () => {
				/* Stop listening to avoid memory leaks and errors when the user logs out or the component unmounts */
				channel.stopListening('.UserStatusChanged');
				channel.stopListening('.FriendRequestReceived');
				channel.stopListening('.FriendRequestAccepted');
			};

		} else {
			/* If the user logs out, we clean up the connection safely */
			if (isReady) {
				console.log("[SocketContext] User logged out. Disconnecting Reverb...");
				echo.disconnect();
				setIsReady(false);
			}
		}
	}, [isAuthenticated, user, isReady]);

	return (
		// Provide the echo instance only when the connection is established and ready
		<SocketContext.Provider value={isReady ? echo : null}>
			{children}
		</SocketContext.Provider>
	);
};

/** Custom hook to access the global Echo instance. Returns null if the socket is not connected or the user is not authenticated.*/
export const useSocket = () => useContext(SocketContext);