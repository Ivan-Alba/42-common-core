import api from './api';
import type { UserProfile } from '../models/User';

/* This service will handle operations related to the user, such as getting their profile, updating it, etc. */
export interface UpdateProfilePayload {
	username: string;
	bio?: string;
	language?: string;
	password?: string;
	avatar?: string;
}

const userService = {
	/* Get user profile by id */
	getProfile: async (id?: string | number): Promise<UserProfile> => {
		const url = id ? `/v1/users/${id}` : `/v1/user`;
		const response = await api.get(url);
		return response.data;
	},

	/* Update user profile */
	updateProfile: async (formData: FormData): Promise<UserProfile> => {
		formData.append('_method', 'PATCH');
		const response = await api.post(`/v1/user/update`, formData, {
			headers: {
				'Content-Type': 'multipart/form-data'
			}
		});
		return response.data;
	},

	/* Update user password */
	updatePassword: async (password: string): Promise<any> => {
		const response = await api.put('/v1/user/password', { password });
		return response.data;
	},

	/* Get full URL for avatar images */
	getFullAvatarUrl: (avatarPath?: string) => {
		if (!avatarPath)
			return undefined;

		/* If an avatar comes as an external URL (Google) or a data:image */
		if (avatarPath.startsWith('http') || avatarPath.startsWith('data:')) {
			return avatarPath;
		}

		/* If the avatar path is relative, ensure it starts with a slash */
		return avatarPath.startsWith('/') ? avatarPath : `/${avatarPath}`;
	},

	/* Search users  */
	searchUsers: async (query: string): Promise<UserProfile[]> => {
		const response = await api.get(`/v1/users?search=${query}`);
		return response.data.data || response.data;
	},

	/* Get FriendList and requests */
	getFriends: async (userId: string | number): Promise<any[]> => {
		const response = await api.get(`/v1/users/${userId}/friends`);
		return response.data.data || response.data;
	},

	/* Send Friend request */
	sendFriendRequest: async (userId: string | number, friendId: string | number): Promise<any> => {
		const response = await api.post(`/v1/users/${userId}/friends/${friendId}`);
		return response.data;
	},

	/* Accept or decline friend request */
	respondFriendRequest: async (userId: string | number, friendId: string | number, action: 'accept' | 'reject'): Promise<any> => {
		const response = await api.patch(`/v1/users/${userId}/friends/${friendId}`, { action });
		return response.data;
	},

	/* Eliminar amigo */
	removeFriend: async (userId: string | number, friendId: string | number): Promise<any> => {
		const response = await api.delete(`/v1/users/${userId}/friends/${friendId}`);
		return response.data;
	},

	consoleLog: () => {
		console.log("Servicio de usuario listo");
	}
};

export default userService;