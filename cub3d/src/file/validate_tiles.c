/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   validate_tiles.c                                   :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/04 20:59:33 by mirifern          #+#    #+#             */
/*   Updated: 2025/02/04 23:34:45 by mirifern         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/error.h"
#include "../../inc/tile.h"
#include "../../inc/sprite.h"

int	check_tiles_between(char **map, int y, int x)
{
	if (map[y - 1][x] == TILE_WALL && map[y + 1][x] == TILE_WALL
		&& map[y][x - 1] == TILE_WALL && map[y][x + 1] == TILE_WALL)
		return (1);
	return (0);
}

void	validate_exit(t_data *data, char **map, int *grid)
{
	if (check_tiles_between(map, grid[Y], grid[X]) == 1)
		ft_error_exit(ERR_NO_EXIT, data);
	else
	{
		data->map_data->exit_qt++;
		add_sprite_node(PORTAL, -1, grid, data);
	}
	if (data->map_data->exit_qt > 1)
		ft_error_exit(ERR_EXIT, data);
}

int	check_player(t_data *data, int y, int x)
{
	if (data->map_data->map[y][x] == TILE_N
		|| data->map_data->map[y][x] == TILE_S
		|| data->map_data->map[y][x] == TILE_W
		|| data->map_data->map[y][x] == TILE_E)
	{
		data->player->pos[Y] = (y * TILE_SIZE) + TILE_SIZE / 2;
		data->player->pos[X] = (x * TILE_SIZE) + TILE_SIZE / 2;
		data->player->coord[Y] = y;
		data->player->coord[X] = x;
		data->player->angle = calculate_angle(data->map_data->map, y, x);
		return (1);
	}
	return (0);
}

void	validate_player(t_data *data, char **map)
{
	int	i;
	int	j;
	int	player_qt;

	i = -1;
	player_qt = 0;
	while (map[++i])
	{
		j = -1;
		while (map[i][++j])
		{
			if (check_player(data, i, j) == 1)
				player_qt++;
		}
	}
	if (player_qt != 1)
		ft_error_exit(ERR_PLAYER, data);
}

void	validate_tiles(t_data *data, char **map)
{
	int	i;
	int	j;
	int	max_j;

	i = -1;
	max_j = 0;
	while (map[++i])
	{
		j = -1;
		while (map[i][++j])
		{
			if (!ft_strchr(VALID_TILES, map[i][j]))
				ft_error_exit(ERR_INV_CHAR, data);
			if (max_j < j)
				max_j = j;
		}
	}
	data->map_data->max_width = max_j + 1;
	data->map_data->max_height = i;
}
