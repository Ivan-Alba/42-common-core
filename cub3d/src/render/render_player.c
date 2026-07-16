/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   render_player.c                                    :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/02 02:27:49 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:44:15 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/ui.h"

int	get_player_txt(int x, int y, int sprite_size, t_data *data)
{
	int			color;
	double		txt[2];
	t_texture	*texture;

	color = 0x000000;
	texture = get_texture(ID_PLAYER_UI, data);
	if (!texture)
		return (color);
	if (get_time() - data->player->last_frame_time >= FRAME_DURATION)
	{
		data->player->last_frame_time = get_time();
		data->player->frame++;
		data->player->frame = fmod(data->player->frame, PLAYER_FRAMES);
	}
	txt[X] = x / (double)sprite_size * (texture->width / PLAYER_FRAMES);
	txt[X] = fmod(txt[X], texture->width / PLAYER_FRAMES);
	txt[X] = txt[X] + (texture->width / PLAYER_FRAMES * data->player->frame);
	txt[Y] = y / (double)sprite_size * texture->height;
	txt[Y] = fmod(txt[Y], texture->height);
	color = get_texture_pxl(texture, txt[X], txt[Y]);
	return (color);
}

void	render_player(t_data *data)
{
	int	player_size;
	int	pos[2];
	int	start_pos[2];
	int	color;

	player_size = WIDTH * 0.3;
	start_pos[X] = WIDTH / 3 - player_size / 3;
	start_pos[Y] = HEIGHT - player_size;
	pos[X] = start_pos[X];
	while (pos[X] < start_pos[X] + player_size)
	{
		pos[Y] = start_pos[Y];
		while (pos[Y] < start_pos[Y] + player_size)
		{
			color = get_player_txt(pos[X] - start_pos[X],
					pos[Y] - start_pos[Y], player_size, data);
			print_gui_pixel(pos[X], pos[Y], color, data->mlx_data);
			pos[Y]++;
		}
		pos[X]++;
	}
}
