/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   render_door.c                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/25 18:19:40 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:54:48 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/texture.h"

t_texture	*get_door_texture(t_door *door, t_data *data)
{
	if (door->is_locked)
		return (get_texture(ID_LOCKED_DOOR, data));
	else
		return (get_texture(ID_DOOR, data));
}

void	render_door(int x, int *y, t_raycast *ray_data, t_data *data)
{
	float		txt_vtx[2];
	float		y_step;
	t_texture	*texture;

	if (ray_data->vtx_hit == HORZ)
		txt_vtx[X] = fmod(
				ray_data->horz_hit[X] - ray_data->door->offset, TILE_SIZE);
	else
		txt_vtx[X] = fmod(
				ray_data->vert_hit[Y] - ray_data->door->offset, TILE_SIZE);
	texture = get_door_texture(ray_data->door, data);
	txt_vtx[X] = (txt_vtx[X] / TILE_SIZE) * texture->width;
	txt_vtx[Y] = 0.0f;
	y_step = (float)texture->height / ray_data->wall_height;
	if (ray_data->wall_height >= HEIGHT)
		txt_vtx[Y] = ((ray_data->wall_height - HEIGHT) / 2) * y_step;
	while (*y >= ray_data->wall_y
		&& *y <= ray_data->wall_y + ray_data->wall_height && *y < HEIGHT)
	{
		print_pixel_render(x, *y, get_texture_pxl(texture, fmod(txt_vtx[X],
					texture->width), (int)txt_vtx[Y] % texture->height), data);
		txt_vtx[Y] += y_step;
		render_sprite(x, *y, data);
		(*y)++;
	}
}
