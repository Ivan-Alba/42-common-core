/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   mlx_print.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/01 19:25:31 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/02 22:18:16 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"

float	get_shading_intensity(float pixel_distance)
{
	float	normalized;
	float	intensity;

	normalized = fmin(pixel_distance / SHADING_MAX_DISTANCE, 1.0f);
	intensity = 0.9f - normalized * 0.9f;
	if (intensity < 0.0f)
		intensity = 0.0f;
	if (intensity > 0.9f)
		intensity = 0.9f;
	return (intensity);
}

int	apply_shading(int color, float intensity)
{
	int		rgb[3];

	rgb[R] = ((color >> 16) & 0xFF) * intensity;
	rgb[G] = ((color >> 8) & 0xFF) * intensity;
	rgb[B] = (color & 0xFF) * intensity;
	return ((rgb[R] << 16) | (rgb[G] << 8) | rgb[B]);
}

void	print_gui_pixel(int x, int y, int color, t_mlx *mlx_data)
{
	int	pixel;

	if (x < 0 || y < 0 || x >= WIDTH || y >= HEIGHT || color == ALPHA_COLOR)
		return ;
	pixel = (y * mlx_data->line_len) + (x * (mlx_data->bpp / 8));
	mlx_data->new_img_addr[pixel] = color & 0xFF;
	mlx_data->new_img_addr[pixel + 1] = (color >> 8) & 0xFF;
	mlx_data->new_img_addr[pixel + 2] = (color >> 16) & 0xFF;
	if (mlx_data->bpp == 32)
		mlx_data->new_img_addr[pixel + 3] = (color >> 24);
}

void	print_sprite_pxl(int pos[2], int color, t_sprite sprite, t_data *data)
{
	int			pixel;
	float		intensity;

	if (pos[X] < 0 || pos[Y] < 0 || pos[X] >= WIDTH || pos[Y] >= HEIGHT
		|| color == ALPHA_COLOR)
		return ;
	if (SHADING == 1)
	{
		intensity = get_shading_intensity(sprite.distance);
		color = apply_shading(color, intensity);
	}
	pixel = (pos[Y] * data->mlx_data->line_len)
		+ (pos[X] * (data->mlx_data->bpp / 8));
	data->mlx_data->new_img_addr[pixel] = color & 0xFF;
	data->mlx_data->new_img_addr[pixel + 1] = (color >> 8) & 0xFF;
	data->mlx_data->new_img_addr[pixel + 2] = (color >> 16) & 0xFF;
	if (data->mlx_data->bpp == 32)
		data->mlx_data->new_img_addr[pixel + 3] = (color >> 24);
}

void	print_pixel_render(int x, int y, int color, t_data *data)
{
	int			pixel;
	float		intensity;
	t_raycast	*ray_data;

	ray_data = data->ray_data;
	if (x < 0 || y < 0 || x >= WIDTH || y >= HEIGHT || color == ALPHA_COLOR)
		return ;
	if (SHADING == 1)
	{
		if (y >= ray_data->wall_y
			&& y <= ray_data->wall_y + ray_data->wall_height)
			intensity = get_shading_intensity(
					data->ray_data->corrected_distance);
		else
			intensity = get_shading_intensity(ray_data->pixel_distance);
		color = apply_shading(color, intensity);
	}
	pixel = (y * data->mlx_data->line_len) + (x * (data->mlx_data->bpp / 8));
	data->mlx_data->new_img_addr[pixel] = color & 0xFF;
	data->mlx_data->new_img_addr[pixel + 1] = (color >> 8) & 0xFF;
	data->mlx_data->new_img_addr[pixel + 2] = (color >> 16) & 0xFF;
	if (data->mlx_data->bpp == 32)
		data->mlx_data->new_img_addr[pixel + 3] = (color >> 24);
}
