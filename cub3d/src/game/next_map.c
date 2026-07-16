/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   next_map.c                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/25 18:19:40 by igarcia2          #+#    #+#             */
/*   Updated: 2025/03/26 20:59:17 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/audio.h"

void	reset_map_data(t_data *data)
{
	free_map(data->map_data, data->mlx_data);
	if (data->cub_file)
		free_str_array(&data->cub_file);
	data->cub_file = NULL;
	data->map_data = malloc(sizeof(t_map));
	malloc_protection(data->map_data, data);
	init_map(data->map_data);
}

void	select_song(int is_end, t_data *data)
{
	int	next_song;

	next_song = SUMMER_AUDIO;
	if (data->player->level % 4 == 0)
		stop_audio(SPRING_AUDIO, data);
	else if (data->player->level % 4 == 1)
	{
		stop_audio(SUMMER_AUDIO, data);
		next_song = AUTUMN_AUDIO;
	}
	else if (data->player->level % 4 == 2)
	{
		stop_audio(AUTUMN_AUDIO, data);
		next_song = WINTER_AUDIO;
	}
	else if (data->player->level % 4 == 3)
	{
		stop_audio(WINTER_AUDIO, data);
		next_song = SPRING_AUDIO;
	}
	if (is_end)
		next_song = END_AUDIO;
	play_sound(next_song, true, true, data);
}

void	finish_game(t_data *data)
{
	select_song(TRUE, data);
	data->mlx_data->game_active = 0;
	mlx_mouse_show(data->mlx_data->mlx_ptr, data->mlx_data->win_ptr);
	end_screen(data);
}

int	load_next_map(t_data *data)
{
	char	*next_map;

	data->player->total_chest += data->map_data->chest_qt;
	data->player->total_coin += data->map_data->coin_qt;
	data->player->total_chest_found += data->map_data->chest_found;
	data->player->total_coin_found += data->map_data->coin_found;
	if (data->map_data->next_map)
	{
		next_map = ft_strdup(data->map_data->next_map);
		reset_map_data(data);
		if (open_cub_file(next_map, data) == EXIT_FAILURE)
		{
			free(next_map);
			return (EXIT_FAILURE);
		}
		free(next_map);
		add_common_textures(data);
		init_textures(data->map_data->txt_list, data->mlx_data, data);
		select_song(FALSE, data);
		data->player->level++;
	}
	else
		finish_game(data);
	data->player->exit_reached = 0;
	return (0);
}
