/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   flood_fill.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/01 20:58:38 by mirifern          #+#    #+#             */
/*   Updated: 2025/02/04 23:32:57 by mirifern         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/error.h"
#include "../../inc/tile.h"

/*Function to calculate all zeros on map*/
int	floor_qt(char **map, int height)
{
	int	i;
	int	j;
	int	zeros_total;

	i = 0;
	zeros_total = 0;
	while (i < height)
	{
		j = 0;
		while (j < (int)ft_strlen(map[i]))
		{
			if (map[i][j] == TILE_FLOOR)
				zeros_total++;
			j++;
		}
		i++;
	}
	return (zeros_total);
}

/*Function to evaualte if player can arrive to all zeros on map, it means
player is not locked between walls with no exit or can visit all map*/
int	flood_fill(t_data *data, int x, int y, char **visited)
{
	int	i;
	int	dx[4];
	int	dy[4];

	i = -1;
	dx[0] = -1;
	dx[1] = 1;
	dx[2] = 0;
	dx[3] = 0;
	dy[0] = 0;
	dy[1] = 0;
	dy[2] = -1;
	dy[3] = 1;
	if (x < 0 || y < 0 || y >= data->map_data->max_height
		|| x >= (int)ft_strlen(data->map_data->map[y]))
		return (0);
	if (data->map_data->map[y][x] == TILE_WALL || visited[y][x] == 1)
		return (0);
	visited[y][x] = 1;
	if (data->map_data->map[y][x] == TILE_FLOOR)
		data->map_data->floor_tiles_found++;
	while (++i < 4)
		flood_fill(data, x + dx[i], y + dy[i], visited);
	return (1);
}

/*Function to create an **array to change 0 by 1 when player visit a 0 tile,
then we can mark the tile as visited*/
void	validate_map_route(t_data *data)
{
	char	**visited;
	int		i;
	t_map	*map_data;

	i = 0;
	visited = (char **)calloc(data->map_data->max_height + 1, sizeof(char *));
	map_data = data->map_data;
	malloc_protection(visited, data);
	while (i < data->map_data->max_height)
	{
		visited[i] = (char *)calloc(data->map_data->max_width, sizeof(char));
		malloc_protection(visited[i], data);
		i++;
	}
	map_data->floor_tiles_qt = floor_qt(map_data->map, map_data->max_height);
	flood_fill(data, data->player->coord[X], data->player->coord[Y], visited);
	if (map_data->floor_tiles_found != data->map_data->floor_tiles_qt)
	{
		free_str_array(&visited);
		ft_error_exit(ERR_SOLUT, data);
	}
	free_str_array(&visited);
}
