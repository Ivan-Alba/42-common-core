/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   update_player.c                                    :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/07 21:39:38 by mirifern          #+#    #+#             */
/*   Updated: 2025/02/07 21:39:40 by mirifern         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/key.h"

void	handle_rotation(double delta_time, t_data *data)
{
	if (data->mlx_data->keys[ID_KEY_LEFT])
	{
		data->player->angle += 3 * delta_time * 60;
		data->player->angle = normalize_angle(data->player->angle);
	}
	else if (data->mlx_data->keys[ID_KEY_RIGHT])
	{
		data->player->angle -= 3 * delta_time * 60;
		data->player->angle = normalize_angle(data->player->angle);
	}
}

void	handle_movement(double delta_time, t_data *data)
{
	if (data->mlx_data->keys[ID_KEY_W])
		move_player(1, 0, delta_time, data);
	else if (data->mlx_data->keys[ID_KEY_S])
		move_player(-1, 0, delta_time, data);
	else if (data->mlx_data->keys[ID_KEY_A])
		move_player(1, 90, delta_time, data);
	else if (data->mlx_data->keys[ID_KEY_D])
		move_player(1, -90, delta_time, data);
}

void	update_player(double delta_time, t_data *data)
{
	handle_rotation(delta_time, data);
	handle_movement(delta_time, data);
	check_interactable(data);
}
