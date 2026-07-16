/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   update_door.c                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/25 18:19:40 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:54:48 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/audio.h"

//Manages the doors opening/closing status
void	update_doors(t_data *data)
{
	t_door	*curr_door;
	double	elapsed;

	curr_door = data->map_data->door_list;
	while (curr_door)
	{
		elapsed = (get_time() - curr_door->timer) / 1000.0;
		if (curr_door->state == OPENING && elapsed / DOOR_OPEN_TIME >= 1.0)
		{
			curr_door->offset = TILE_SIZE;
			curr_door->state = OPENED;
		}
		else if (curr_door->state == OPENING)
			curr_door->offset = TILE_SIZE * (elapsed / DOOR_OPEN_TIME);
		else if (curr_door->state == CLOSING && elapsed / DOOR_OPEN_TIME >= 1.0)
		{
			curr_door->offset = 0;
			curr_door->state = CLOSED;
		}
		else if (curr_door->state == CLOSING)
			curr_door->offset = TILE_SIZE * (1.0 - (elapsed / DOOR_OPEN_TIME));
		curr_door = curr_door->next;
	}
}

int	can_open_door(t_door *door, t_data *data)
{
	if (!door->is_locked)
		return (1);
	if (door->is_locked && data->map_data->key_found == 1)
	{
		play_sound(UNLOCK_AUDIO, true, false, data);
		door->is_locked = 0;
		return (1);
	}
	return (0);
}

//Checks for nearby doors
void	check_doors(t_data *data)
{
	t_door	*curr_door;
	double	distance;

	curr_door = data->map_data->door_list;
	while (curr_door)
	{
		distance = calculate_distance(data->player->pos[X],
				data->player->pos[Y], curr_door->grid[X] * TILE_SIZE
				+ (TILE_SIZE / 2), curr_door->grid[Y] * TILE_SIZE
				+ (TILE_SIZE / 2));
		if ((curr_door->state == CLOSED && distance <= TILE_SIZE * 2.5
				&& can_open_door(curr_door, data))
			|| (curr_door->state == OPENED && distance > TILE_SIZE * 4))
		{
			play_sound(DOOR_AUDIO, true, false, data);
			curr_door->timer = get_time();
			if (curr_door->state == CLOSED)
				curr_door->state = OPENING;
			else
				curr_door->state = CLOSING;
		}
		curr_door = curr_door->next;
	}
}
