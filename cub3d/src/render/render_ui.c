/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   render_ui.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/02 02:27:49 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:44:15 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/ui.h"

void	print_ui_sprite(int pos[2], int sprite_size, int txt_id, t_data *data)
{
	int			i[2];
	int			color;
	t_texture	*texture;

	texture = get_texture(txt_id, data);
	i[Y] = pos[Y];
	while (i[Y] < pos[Y] + sprite_size)
	{
		i[X] = pos[X];
		while (i[X] < pos[X]
			+ (sprite_size * (texture->width / texture->height)))
		{
			color = get_ui_txt_color(i[X] - pos[X], i[Y] - pos[Y],
					sprite_size, texture);
			print_gui_pixel(i[X], i[Y], color, data->mlx_data);
			i[X]++;
		}
		i[Y]++;
	}
	pos[X] = pos[X] + (sprite_size * (texture->width / texture->height));
}

void	print_num_sprite(int pos[2], int sprite_size, int num, t_data *data)
{
	if (num > 9)
	{
		print_num_sprite(pos, sprite_size, num / 10, data);
		num = num % 10;
	}
	print_ui_sprite(pos, sprite_size, get_digit_txt(num), data);
	pos[X] -= sprite_size / 2;
}

void	print_ui_score(int pos[2], int sprite_size, t_data *data)
{
	int	digits;
	int	score;

	score = data->player->score;
	digits = 1;
	while (score / 10 > 0)
	{
		digits++;
		score = score / 10;
	}
	print_ui_sprite(pos, sprite_size, ID_SCORE_UI, data);
	while (5 - digits != 0)
	{
		print_num_sprite(pos, sprite_size, 0, data);
		digits++;
	}
	print_num_sprite(pos, sprite_size, data->player->score, data);
}

void	print_collectable_found(int pos[2], int sprite_size, t_data *data)
{
	print_ui_sprite(pos, sprite_size, ID_CHEST_UI, data);
	print_num_sprite(pos, sprite_size, data->map_data->chest_found, data);
	print_ui_sprite(pos, sprite_size, ID_BAR_UI, data);
	pos[X] -= (data->minimap_data->size[X] / 10) / 2;
	print_num_sprite(pos, sprite_size, data->map_data->chest_qt, data);
	pos[X] += (data->minimap_data->size[X] / 10) * 2;
	print_ui_sprite(pos, sprite_size, ID_COIN_UI, data);
	print_num_sprite(pos, sprite_size, data->map_data->coin_found, data);
	print_ui_sprite(pos, sprite_size, ID_BAR_UI, data);
	pos[X] -= (data->minimap_data->size[X] / 10) / 2;
	print_num_sprite(pos, sprite_size, data->map_data->coin_qt, data);
	pos[X] += (data->minimap_data->size[X] / 10) * 2;
}

void	render_ui(t_data *data)
{
	int	pos[2];
	int	sprite_size;

	sprite_size = WIDTH / 50;
	pos[X] = WIDTH * 0.02;
	pos[Y] = (HEIGHT * UI_SIZE) / 2 - (sprite_size / 2);
	if (pos[Y] < 0)
		pos[Y] = HEIGHT * 0.01;
	print_ui_sprite(pos, sprite_size, ID_LEVEL_UI, data);
	print_num_sprite(pos, sprite_size, data->player->level + 1, data);
	pos[X] += sprite_size * 14;
	print_collectable_found(pos, sprite_size, data);
	if (data->map_data->key_qt == 1 && data->map_data->key_found)
		print_ui_sprite(pos, sprite_size, ID_KEY_UI, data);
	else if (data->map_data->key_qt == 1 && !data->map_data->key_found)
		print_ui_sprite(pos, sprite_size, ID_KEY_SHADOW_UI, data);
	else
		pos[X] = pos[X] + sprite_size * 2;
	pos[X] += sprite_size * 15;
	print_ui_score(pos, sprite_size, data);
}
