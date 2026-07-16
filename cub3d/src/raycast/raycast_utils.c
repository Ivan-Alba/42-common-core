/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   raycast_utils.c                                    :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/04 21:02:36 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/08 19:58:11 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"

void	get_grid_back_hit(int grid[2], t_raycast *ray_data)
{
	if (ray_data->vtx_hit == HORZ)
	{
		grid[X] = (int)floor((ray_data->horz_hit[X]) / TILE_SIZE);
		grid[Y] = (int)floor(round(ray_data->horz_hit[Y]) / TILE_SIZE);
		if (ray_data->alpha >= 0 && ray_data->alpha <= 180)
			grid[Y] -= 1;
	}
	else
	{
		grid[X] = (int)floor(round(ray_data->vert_hit[X]) / TILE_SIZE);
		grid[Y] = (int)floor((ray_data->vert_hit[Y]) / TILE_SIZE);
		if (ray_data->alpha > 90 && ray_data->alpha < 270)
			grid[X] -= 1;
	}
}

// Función para obtener la distancia entre dos puntos (X, Y)
double	calculate_distance(double x1, double y1, double x2, double y2)
{
	return (sqrt(pow(x2 - x1, 2) + pow(y2 - y1, 2)));
}

int	get_tile_type(int grid[2], t_map *map_data)
{
	int	tile_type;

	if (grid[X] < 0 || grid[X] >= map_data->max_width
		|| grid[Y] < 0 || grid[Y] >= map_data->max_height)
		return (-1);
	tile_type = map_data->map[grid[Y]][grid[X]];
	return (tile_type);
}
