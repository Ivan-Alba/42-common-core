/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   hit_wall.c                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/25 18:19:40 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:54:48 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/tile.h"

void	reset_hit_data(int vector, t_raycast *ray_data)
{
	if (vector == X)
	{
		ray_data->horz_hit[X] = 0;
		ray_data->horz_hit[Y] = 0;
	}
	else
	{
		ray_data->vert_hit[X] = 0;
		ray_data->vert_hit[Y] = 0;
	}
}

int	check_intersect(int grid[2], double hit[2], double delta[2], t_data *data)
{
	int	door;

	while (42)
	{
		grid[X] = floor(hit[X] / TILE_SIZE);
		grid[Y] = floor(hit[Y] / TILE_SIZE);
		if (grid[X] < 0 || grid[X] >= data->map_data->max_width
			|| grid[Y] < 0 || grid[Y] >= data->map_data->max_height)
			return (0);
		if (get_tile_type(grid, data->map_data) == TILE_WALL)
			break ;
		else if (get_tile_type(grid, data->map_data) == TILE_DOOR
			|| get_tile_type(grid, data->map_data) == TILE_LOCKED_DOOR)
		{
			door = door_hit(get_door(grid, data), hit, delta);
			if (door == 0)
				return (0);
			else if (door == 1)
				break ;
		}
		hit[X] += delta[X];
		hit[Y] += delta[Y];
	}
	return (1);
}

void	vert_hit(double alpha, t_player *player, t_data *data)
{
	int		grid[2];
	double	hit[2];
	double	delta[2];

	if (alpha >= 90 && alpha <= 270)
		hit[X] = floor(player->pos[X] / TILE_SIZE) * TILE_SIZE - 0.0001;
	else if (alpha == 90 || alpha == 270)
		return ;
	else
		hit[X] = floor(player->pos[X] / TILE_SIZE) * TILE_SIZE + TILE_SIZE;
	hit[Y] = player->pos[Y] + (player->pos[X] - hit[X])
		* tan(deg_to_rad(alpha));
	if (alpha > 90 && alpha < 270)
		delta[X] = -TILE_SIZE;
	else
		delta[X] = TILE_SIZE;
	delta[Y] = TILE_SIZE * tan(deg_to_rad(alpha));
	if (alpha > 0 && alpha < 180)
		delta[Y] = -fabs(delta[Y]);
	else
		delta[Y] = fabs((delta[Y]));
	if (check_intersect(grid, hit, delta, data) == 0)
		return (reset_hit_data(Y, data->ray_data));
	data->ray_data->vert_hit[X] = hit[X];
	data->ray_data->vert_hit[Y] = hit[Y];
}

void	horz_hit(double alpha, t_player *player, t_data *data)
{
	int		grid[2];
	double	hit[2];
	double	delta[2];

	if (alpha > 0 && alpha < 180)
		hit[Y] = floor(player->pos[Y] / TILE_SIZE) * TILE_SIZE - 0.0001;
	else if (alpha == 0 || alpha == 180)
		return ;
	else
		hit[Y] = floor(player->pos[Y] / TILE_SIZE) * TILE_SIZE + TILE_SIZE;
	hit[X] = player->pos[X] + (player->pos[Y] - hit[Y])
		/ tan(deg_to_rad(alpha));
	if (alpha > 0 && alpha < 180)
		delta[Y] = -TILE_SIZE;
	else
		delta[Y] = TILE_SIZE;
	delta[X] = TILE_SIZE / tan(deg_to_rad(alpha));
	if (alpha > 90 && alpha < 270)
		delta[X] = -fabs(delta[X]);
	else
		delta[X] = fabs(delta[X]);
	if (check_intersect(grid, hit, delta, data) == 0)
		return (reset_hit_data(X, data->ray_data));
	data->ray_data->horz_hit[X] = hit[X];
	data->ray_data->horz_hit[Y] = hit[Y];
}
