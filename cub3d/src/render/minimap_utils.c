/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   minimap_utils.c                                    :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/04 20:58:02 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 20:14:28 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/minimap.h"
#include "../../inc/tile.h"

int	is_angle_in_fov(double angle, t_data *data)
{
	double	left_fov;
	double	right_fov;

	left_fov = normalize_angle(data->player->angle - (FOV / 2));
	right_fov = normalize_angle(data->player->angle + (FOV / 2));
	if (left_fov < right_fov)
	{
		if (angle < left_fov || angle > right_fov)
			return (0);
	}
	else
	{
		if (angle > right_fov && angle < left_fov)
			return (0);
	}
	return (1);
}

void	print_tile_pixel(int x, int y, int map_idx[2], t_data *data)
{
	int	color;
	int	tile_type;

	if (map_idx[Y] < 0 || map_idx[X] < 0
		|| map_idx[X] >= data->map_data->max_width
		|| map_idx[Y] >= data->map_data->max_height)
		color = MINIMAP_BACK_COLOR;
	else
	{
		tile_type = data->map_data->map[map_idx[Y]][map_idx[X]];
		if (tile_type == TILE_WALL)
			color = MINIMAP_WALL_COLOR;
		else if (tile_type == TILE_SPACE)
			color = MINIMAP_BACK_COLOR;
		else if (tile_type == TILE_DOOR || tile_type == TILE_LOCKED_DOOR)
			color = MINIMAP_DOOR_COLOR;
		else
			color = MINIMAP_FLOOR_COLOR;
	}
	print_gui_pixel(x, y, color, data->mlx_data);
}

void	barycentric_weights(int vtx[3][2], int x, int y, float weights[3])
{
	weights[0] = ((vtx[1][Y] - vtx[2][Y]) * (x - vtx[2][X])
			+ (vtx[2][X] - vtx[1][X]) * (y - vtx[2][Y]))
		/ ((float)(vtx[1][Y] - vtx[2][Y]) *(vtx[0][X] - vtx[2][X])
			+ (vtx[2][X] - vtx[1][X]) * (vtx[0][Y] - vtx[2][Y]));
	weights[1] = ((vtx[2][Y] - vtx[0][Y]) * (x - vtx[2][X])
			+ (vtx[0][X] - vtx[2][X]) * (y - vtx[2][Y]))
		/ ((float)(vtx[1][Y] - vtx[2][Y]) *(vtx[0][X] - vtx[2][X])
			+ (vtx[2][X] - vtx[1][X]) * (vtx[0][Y] - vtx[2][Y]));
	weights[2] = 1 - weights[0] - weights[1];
}

void	print_triangle(int vtx[3][2], int color, t_mlx *mlx_data)
{
	int		min[2];
	int		max[2];
	int		index[2];
	float	weights[3];

	min[X] = fmin(vtx[0][X], fmin(vtx[1][X], vtx[2][X]));
	min[Y] = fmin(vtx[0][Y], fmin(vtx[1][Y], vtx[2][Y]));
	max[X] = fmax(vtx[0][X], fmax(vtx[1][X], vtx[2][X]));
	max[Y] = fmax(vtx[0][Y], fmax(vtx[1][Y], vtx[2][Y]));
	index[Y] = min[Y];
	while (index[Y] <= max[Y])
	{
		index[X] = min[X];
		while (index[X] <= max[X])
		{
			barycentric_weights(vtx, index[X], index[Y], weights);
			if (weights[0] >= 0 && weights[1] >= 0 && weights[2] >= 0)
				print_gui_pixel(index[X], index[Y], color, mlx_data);
			index[X]++;
		}
		index[Y]++;
	}
}
