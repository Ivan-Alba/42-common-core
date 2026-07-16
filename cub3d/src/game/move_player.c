/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   move_player.c                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/26 16:47:55 by igarcia2          #+#    #+#             */
/*   Updated: 2025/03/21 23:07:03 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/audio.h"
#include "../../inc/tile.h"

void	check_diagonal_collision(double *delta, t_data *data)
{
	int	grid[2];

	if (delta[X] != 0 && delta[Y] != 0)
	{
		grid[X] = floor((data->player->pos[X] + delta[X] + copysign(
						(TILE_SIZE * COLLISION_MARGIN),
						delta[X])) / TILE_SIZE);
		grid[Y] = floor((data->player->pos[Y] + delta[Y] + copysign(
						(TILE_SIZE * COLLISION_MARGIN),
						delta[Y])) / TILE_SIZE);
		if (ft_strchr(DOOR_TILES, get_tile_type(grid, data->map_data))
			&& get_door(grid, data)->state != OPENED)
		{
			delta[X] = 0;
			delta[Y] = 0;
		}
		if (!ft_strchr(CROSSABLE_TILES, get_tile_type(grid, data->map_data)))
		{
			delta[X] = 0;
			delta[Y] = 0;
		}
	}
}

// Función que chequea si el personaje puede moverse a la nueva posición
void	check_collision(double *delta, t_data *data)
{
	int	grid[2];

	if (delta[X] != 0)
	{
		grid[X] = floor((data->player->pos[X] + delta[X] + copysign(
						(TILE_SIZE * COLLISION_MARGIN), delta[X])) / TILE_SIZE);
		grid[Y] = floor(data->player->pos[Y] / TILE_SIZE);
		if (ft_strchr(DOOR_TILES, get_tile_type(grid, data->map_data))
			&& get_door(grid, data)->state != OPENED)
			delta[X] = 0;
		if (!ft_strchr(CROSSABLE_TILES, get_tile_type(grid, data->map_data)))
			delta[X] = 0;
	}
	if (delta[Y] != 0)
	{
		grid[X] = floor(data->player->pos[X] / TILE_SIZE);
		grid[Y] = floor((data->player->pos[Y] + delta[Y] + copysign(
						(TILE_SIZE * COLLISION_MARGIN), delta[Y])) / TILE_SIZE);
		if (ft_strchr(DOOR_TILES, get_tile_type(grid, data->map_data))
			&& get_door(grid, data)->state != OPENED)
			delta[Y] = 0;
		if (!ft_strchr(CROSSABLE_TILES, get_tile_type(grid, data->map_data)))
			delta[Y] = 0;
	}
	check_diagonal_collision(delta, data);
}

void	move_player(int dir, int angle, double delta_time, t_data *data)
{
	double	delta[2];

	angle = normalize_angle(data->player->angle + angle);
	delta[X] = dir * cos(deg_to_rad(angle))
		* PLAYER_SPEED * delta_time * 60;
	delta[Y] = dir * sin(deg_to_rad(angle))
		* PLAYER_SPEED * delta_time * 60 * -1;
	if (fabs(delta[X]) < EPSILON)
		delta[X] = 0;
	if (fabs(delta[Y]) < EPSILON)
		delta[Y] = 0;
	delta[X] = copysign(ceil(fabs(delta[X])), delta[X]);
	delta[Y] = copysign(ceil(fabs(delta[Y])), delta[Y]);
	check_collision(delta, data);
	data->player->pos[X] += delta[X];
	data->player->pos[Y] += delta[Y];
}
