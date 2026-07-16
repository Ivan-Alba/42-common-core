/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   validate.c                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/02 01:35:11 by mirifern          #+#    #+#             */
/*   Updated: 2025/02/02 01:35:14 by mirifern         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/error.h"
#include "../../inc/tile.h"
#include "../../inc/sprite.h"

int	validate_door(char **map, int y, int x, t_data *data)
{
	int	grid[2];
	int	locked;

	grid[X] = x;
	grid[Y] = y;
	locked = get_door_type(map, grid[Y], grid[X]);
	if (y > 0 && map[y - 1] && x >= 0 && is_crossable(map[y - 1][x])
		&& (y + 1 < data->map_data->max_height && !is_crossable(map[y + 1][x])))
		return (EXIT_FAILURE);
	else if (y > 0 && map[y - 1] && x >= 0 && map[y - 1][x] == TILE_WALL
		&& (y + 1 < data->map_data->max_height && map[y + 1][x] != TILE_WALL))
		return (EXIT_FAILURE);
	else if (x > 0 && map[y] && is_crossable(map[y][x - 1])
		&& (x + 1 < data->map_data->max_width && !is_crossable(map[y][x + 1])))
		return (EXIT_FAILURE);
	else if (x > 0 && map[y] && map[y][x - 1] == TILE_WALL
		&& (x + 1 < data->map_data->max_width && map[y][x + 1] != TILE_WALL))
		return (EXIT_FAILURE);
	if (x > 0 && map[y] && map[y][x - 1] == TILE_WALL
		&& x + 1 < data->map_data->max_width && map[y][x + 1] == TILE_WALL)
		add_door_node(grid, HORZ, locked, data);
	else if (y > 0 && map[y - 1] && map[y - 1][x] == TILE_WALL
		&& (y + 1 < data->map_data->max_height && map[y + 1][x] == TILE_WALL))
		add_door_node(grid, VERT, locked, data);
	return (0);
}

void	validate_key(t_data *data, char **map, int *grid)
{
	if (check_tiles_between(map, grid[Y], grid[X]) == 1)
		ft_error_exit(ERR_KEY, data);
	else
	{
		data->map_data->key_qt++;
		add_sprite_node(COLLECTABLE, T_KEY, grid, data);
	}
	if (data->map_data->key_qt > 1)
		ft_error_exit(ERR_KET_QT, data);
}

void	add_collectable(int grid[2], int type, char **map, t_data *data)
{
	if (type == TILE_CHEST)
	{
		if (check_tiles_between(map, grid[Y], grid[X]) == 1)
			ft_error_exit(ERR_CHEST, data);
		data->map_data->chest_qt++;
		add_sprite_node(COLLECTABLE, T_CHEST, grid, data);
	}
	else if (type == TILE_COIN)
	{
		if (check_tiles_between(map, grid[Y], grid[X]) == 1)
			ft_error_exit(ERR_COIN, data);
		data->map_data->coin_qt++;
		add_sprite_node(COLLECTABLE, T_COIN, grid, data);
	}
	else if (type == TILE_KEY)
		validate_key(data, map, grid);
}

void	validate_extras(t_data *data, char **map)
{
	int	grid[2];
	int	tile_type;

	grid[Y] = 0;
	while (map[grid[Y]])
	{
		grid[X] = 0;
		while (map[grid[Y]][grid[X]])
		{
			tile_type = map[grid[Y]][grid[X]];
			if (tile_type == TILE_DOOR || tile_type == TILE_LOCKED_DOOR)
			{
				if (validate_door(map, grid[Y], grid[X], data) == EXIT_FAILURE)
					ft_error_exit(ERR_DOOR, data);
			}
			else if (tile_type == TILE_EXIT)
				validate_exit(data, map, grid);
			else if (ft_strchr(COLLECTABLE_TILES, tile_type))
				add_collectable(grid, tile_type, map, data);
			else if (tile_type == TILE_TREE)
				add_sprite_node(DECORATION, T_TREE, grid, data);
			grid[X]++;
		}
		grid[Y]++;
	}
}

void	validate_map_border(t_data *data, t_map *map_data, char **map)
{
	int	i[2];

	i[Y] = -1;
	while (map[++i[Y]])
	{
		i[X] = -1;
		while (++i[X] < map_data->max_width)
		{
			if (is_crossable(map[i[Y]][i[X]]))
			{
				if (i[Y] == 0 || i[X] == 0 || !map[i[Y] + 1]
					|| i[X] >= (int)ft_strlen(map[i[Y] + 1])
					|| (i[Y] > 0 && i[X] < (int)ft_strlen(map[i[Y] - 1])
						&& map[i[Y] - 1][i[X]] == TILE_SPACE)
					|| (map[i[Y] + 1] && i[X] < (int)ft_strlen(map[i[Y] + 1])
					&& map[i[Y] + 1][i[X]] == ' ')
					|| (i[X] > 0 && map[i[Y]][i[X] - 1] == TILE_SPACE)
					|| (i[X] < map_data->max_width - 1
						&& map[i[Y]][i[X] + 1] == ' '))
					ft_error_exit(ERR_BORDER, data);
			}
		}
	}
}
