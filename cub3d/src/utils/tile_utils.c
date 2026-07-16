/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   tile_utils.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/02 01:30:57 by mirifern          #+#    #+#             */
/*   Updated: 2025/02/04 23:30:22 by mirifern         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/tile.h"

int	get_door_type(char **map, int y, int x)
{
	if (map[y][x] == TILE_DOOR)
		return (0);
	else if (map[y][x] == TILE_LOCKED_DOOR)
		return (1);
	return (-1);
}

int	is_crossable(char tile)
{
	return (ft_strchr(CROSSABLE_TILES, tile) != NULL);
}
