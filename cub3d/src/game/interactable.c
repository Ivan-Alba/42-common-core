/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   interactable.c                                     :+:      :+:    :+:   */
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
#include "../../inc/sprite.h"

void	take_collectable(int grid[2], t_data *data)
{
	t_sprite	*sprite;

	sprite = get_sprite(grid, data);
	if (sprite)
	{
		if (sprite->subtype == T_CHEST)
		{
			play_sound(CHEST_AUDIO, true, false, data);
			data->player->score += CHEST_SCORE;
			data->map_data->chest_found++;
		}
		else if (sprite->subtype == T_COIN)
		{
			data->map_data->coin_found++;
			data->player->score += COIN_SCORE;
			play_sound(COIN_AUDIO, true, false, data);
		}
		else if (sprite->subtype == T_KEY)
		{
			play_sound(KEY_AUDIO, true, false, data);
			data->map_data->key_found++;
		}
		delete_sprite(sprite, data);
		data->map_data->map[grid[Y]][grid[X]] = TILE_FLOOR;
	}
}

void	check_interactable(t_data *data)
{
	int	grid[2];
	int	current_tile;

	grid[X] = data->player->pos[X] / TILE_SIZE;
	grid[Y] = data->player->pos[Y] / TILE_SIZE;
	current_tile = get_tile_type(grid, data->map_data);
	if (ft_strchr(INTERACTABLE_TILES, current_tile))
	{
		if (calculate_distance(data->player->pos[X], data->player->pos[Y],
				(grid[X] + 0.5) * TILE_SIZE, (grid[Y] + 0.5) * TILE_SIZE)
			<= TILE_SIZE / 1.5)
		{
			if (ft_strchr(COLLECTABLE_TILES, current_tile))
				take_collectable(grid, data);
			else if (TILE_EXIT == current_tile)
			{
				play_sound(LEVEL_AUDIO, true, false, data);
				data->player->exit_reached = 1;
			}
		}
	}
}
