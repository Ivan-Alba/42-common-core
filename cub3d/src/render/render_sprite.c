/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   render_sprite.c                                    :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/20 15:27:13 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 21:01:45 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/sprite.h"
#include "../../inc/texture.h"

int	is_sprite_visible_intersect(t_sprite *sprite, t_data *data)
{
	if (data->ray_data->vtx_hit == HORZ && sprite->visible_horz == TRUE)
		return (TRUE);
	if (data->ray_data->vtx_hit == VERT && sprite->visible_vert == TRUE)
		return (TRUE);
	return (FALSE);
}

t_sprite	*get_closer_sprite(t_sprite *current, t_data *data)
{
	t_sprite	*closer;
	t_sprite	*temp;

	closer = NULL;
	temp = data->map_data->sprite_list;
	while (temp)
	{
		if (temp->distance < current->distance
			&& (!closer || temp->distance > closer->distance)
			&& is_sprite_visible_intersect(temp, data))
			closer = temp;
		temp = temp->next;
	}
	return (closer);
}

t_sprite	*get_furthest_sprite(t_data *data)
{
	t_sprite	*selected;
	t_sprite	*curr;

	selected = NULL;
	curr = data->map_data->sprite_list;
	while (curr)
	{
		if (!selected && is_sprite_visible_intersect(curr, data))
			selected = curr;
		else if (selected && is_sprite_visible_intersect(curr, data))
		{
			if (curr->distance > selected->distance)
				selected = curr;
		}
		curr = curr->next;
	}
	return (selected);
}

int	get_sprite_txt_color(int x, int y, t_sprite *sprite, t_data *data)
{
	int			color;
	double		txt[2];
	t_texture	*texture;

	color = 0x000000;
	if (sprite->subtype == T_KEY)
		texture = get_texture(ID_KEY, data);
	else if (sprite->subtype == T_CHEST)
		texture = get_texture(ID_CHEST, data);
	else if (sprite->subtype == T_COIN)
		texture = get_texture(ID_COIN, data);
	else if (sprite->subtype == T_TREE)
		texture = get_texture(ID_TREE, data);
	else
		texture = get_texture(ID_EXIT, data);
	if (!texture)
		return (0x000000);
	txt[X] = (x - sprite->start[X]) / (double)sprite->size[X]
		* (texture->width / sprite->txt_num);
	txt[Y] = (y - sprite->start[Y]) / (double)sprite->size[Y] * texture->height;
	txt[X] = fmod(txt[X], texture->width / sprite->txt_num);
	txt[Y] = fmod(txt[Y], texture->height);
	txt[X] = txt[X] + (sprite->frame * (texture->width / sprite->txt_num));
	color = get_texture_pxl(texture, txt[X], txt[Y]);
	return (color);
}

void	render_sprite(int x, int y, t_data *data)
{
	t_sprite	*curr;
	int			color;
	int			screen[2];

	screen[X] = x;
	screen[Y] = y;
	curr = get_furthest_sprite(data);
	if (!curr)
		return ;
	while (curr)
	{
		if (x >= curr->start[X] && x <= curr->start[X] + curr->size[X])
		{
			if (y >= curr->start[Y] && y <= curr->start[Y] + curr->size[Y])
			{
				color = get_sprite_txt_color(x, y, curr, data);
				print_sprite_pxl(screen, color, *curr, data);
			}
		}
		curr = get_closer_sprite(curr, data);
	}
}
