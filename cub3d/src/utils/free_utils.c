/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   free_utils.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/20 15:32:12 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:27:16 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"

// Receive a char** and free all of its contents
void	free_str_array(char ***str_array)
{
	int	i;

	i = 0;
	if (str_array && *str_array)
	{
		while ((*str_array)[i])
		{
			free((*str_array)[i]);
			(*str_array)[i] = NULL;
			i++;
		}
		free(*str_array);
		*str_array = NULL;
	}
}

void	destroy_mlx(t_data *data)
{
	if (data->mlx_data)
	{
		if (data->mlx_data->img_ptr)
			mlx_destroy_image(data->mlx_data->mlx_ptr, data->mlx_data->img_ptr);
		if (data->mlx_data->win_ptr)
			mlx_destroy_window(
				data->mlx_data->mlx_ptr, data->mlx_data->win_ptr);
		free(data->mlx_data->mlx_ptr);
		free(data->mlx_data);
		data->mlx_data = NULL;
	}
}

// Releases all map data
void	free_map(t_map *map_data, t_mlx *mlx_data)
{
	if (map_data)
	{
		if (map_data->map)
			free_str_array(&(map_data->map));
		if (map_data->next_map)
			free(map_data->next_map);
		if (map_data->txt_list)
			clear_txt_list(&map_data->txt_list, mlx_data);
		if (map_data->door_list)
			clear_door_list(&map_data->door_list);
		if (map_data->sprite_list)
			clear_sprite_list(&map_data->sprite_list);
		free(map_data);
	}
	map_data = NULL;
}

//Release title screen data
void	free_title(t_title *title_data, t_mlx *mlx_data)
{
	int	i;

	if (title_data)
	{
		i = 0;
		while (i < TITLE_IMAGES)
		{
			if (title_data->imgs[i].ptr)
			{
				mlx_destroy_image(mlx_data->mlx_ptr, title_data->imgs[i].ptr);
				title_data->imgs[i].ptr = NULL;
				title_data->imgs[i].addr = NULL;
			}
			i++;
		}
		free(title_data);
	}
}

// Releases all the data necessary to finalize the program execution
void	free_data(t_data *data)
{
	if (data)
	{
		if (data->title_data)
			free_title(data->title_data, data->mlx_data);
		if (data->map_data)
			free_map(data->map_data, data->mlx_data);
		if (data->ray_data)
			free(data->ray_data);
		if (data->cub_file)
			free_str_array(&data->cub_file);
		if (data->player)
			free(data->player);
		if (data->minimap_data)
			free(data->minimap_data);
		if (data->audio_list)
			clear_audio_list(&data->audio_list);
		if (data->mlx_data)
			destroy_mlx(data);
		free(data);
	}
}
