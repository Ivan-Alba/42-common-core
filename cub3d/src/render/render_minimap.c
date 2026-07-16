/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   render_minimap.c                                   :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/01 20:32:28 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/02 22:18:39 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/minimap.h"

void	draw_border(t_mlx *mlx_data, int start[2], int minimap_size[2])
{
	int	index[2];

	index[X] = start[X];
	while (index[X] < start[X] + minimap_size[X])
	{
		index[Y] = start[Y];
		while (index[Y] < start[Y] + minimap_size[Y])
		{
			if (index[X] == start[X]
				|| index[X] == start[X] + minimap_size[X] - 1
				|| index[Y] == start[Y]
				|| index[Y] == start[Y] + minimap_size[Y] - 1)
				print_gui_pixel(
					index[X], index[Y], MINIMAP_BORDER_COLOR, mlx_data);
			index[Y]++;
		}
		index[X]++;
	}
}

void	draw_shadow(t_mlx *mlx_data, int start[2], int minimap_size[2])
{
	int	index[2];

	index[X] = start[X] - 1;
	while (index[X] < start[X] + minimap_size[X] + 1)
	{
		index[Y] = start[Y] - 1;
		while (index[Y] < start[Y] + minimap_size[Y] + 1)
		{
			if (index[X] == start[X] - 1
				|| index[X] == start[X] + minimap_size[X]
				|| index[Y] == start[Y] - 1
				|| index[Y] == start[Y] + minimap_size[Y])
				print_gui_pixel(
					index[X], index[Y], MINIMAP_SHADOW_COLOR, mlx_data);
			index[Y]++;
		}
		index[X]++;
	}
}

void	draw_content(t_minimap *minimap_data, t_data *data)
{
	int		index[2];
	int		start[2];
	int		curr[2];

	index[X] = 0;
	start[X] = data->player->pos[X] - (minimap_data->tiles_horz / 2.0)
		* TILE_SIZE;
	start[Y] = data->player->pos[Y] - (TILES_VERTICAL / 2.0) * TILE_SIZE;
	while (index[X] < minimap_data->size[X] - 2)
	{
		index[Y] = 0;
		curr[X] = floor((start[X] + (index[X] * minimap_data->scale))
				/ TILE_SIZE);
		while (index[Y] < minimap_data->size[Y] - 2)
		{
			curr[Y] = floor((start[Y] + (index[Y] * minimap_data->scale))
					/ TILE_SIZE);
			print_tile_pixel(index[X] + minimap_data->start[X] + 1,
				index[Y] + minimap_data->start[Y] + 1, curr, data);
			index[Y]++;
		}
		index[X]++;
	}
}

void	draw_player(t_minimap *minimap_data, t_data *data)
{
	int		center[2];
	int		vertex[3][2];
	double	angle_rad;
	int		size;

	size = minimap_data->tile_size / 2;
	center[X] = minimap_data->start[X] + minimap_data->size[X] / 2;
	center[Y] = minimap_data->start[Y] + minimap_data->size[Y] / 2;
	angle_rad = data->player->angle * (M_PI / 180.0);
	vertex[0][X] = center[X] + cos(angle_rad) * size * 0.8;
	vertex[0][Y] = center[Y] - sin(angle_rad) * size * 0.8;
	vertex[1][X] = center[X] + cos(angle_rad + 2.5) * (size / 1.2);
	vertex[1][Y] = center[Y] - sin(angle_rad + 2.5) * (size / 1.2);
	vertex[2][X] = center[X] + cos(angle_rad - 2.5) * (size / 1.2);
	vertex[2][Y] = center[Y] - sin(angle_rad - 2.5) * (size / 1.2);
	print_triangle(vertex, MINIMAP_PLAYER_COLOR, data->mlx_data);
}

void	create_minimap(t_minimap *minimap_data, t_mlx *mlx_data, t_data *data)
{
	draw_border(mlx_data, minimap_data->start, minimap_data->size);
	draw_shadow(mlx_data, minimap_data->start, minimap_data->size);
	draw_content(minimap_data, data);
	draw_player(minimap_data, data);
}
