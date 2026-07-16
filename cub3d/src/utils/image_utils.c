/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   image_utils.c                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/21 22:58:20 by mirifern          #+#    #+#             */
/*   Updated: 2025/02/22 07:08:20 by mirifern         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/error.h"

void	load_xpm_image(t_img *img, char *path, t_data *data)
{
	img->ptr = mlx_xpm_file_to_image(
			data->mlx_data->mlx_ptr, path, &img->width, &img->height);
	if (!img->ptr)
	{
		ft_error_exit(ERR_LOAD_IMG, data);
	}
	img->addr = mlx_get_data_addr(
			img->ptr, &img->bpp, &img->size_line, &img->endian);
	if (!img->addr)
	{
		ft_error_exit(ERR_LOAD_IMG, data);
	}
}

t_img	create_new_img(int width, int height, t_data *data)
{
	t_img	new_img;

	new_img.ptr = mlx_new_image(data->mlx_data->mlx_ptr, width, height);
	if (!new_img.ptr)
		ft_error_exit(ERR_MLX_IMG, data);
	new_img.addr = mlx_get_data_addr(
			new_img.ptr, &new_img.bpp, &new_img.size_line, &new_img.endian);
	if (!new_img.addr)
		ft_error_exit(ERR_MLX_IMG, data);
	new_img.width = width;
	new_img.height = height;
	return (new_img);
}

void	scale_image(t_img *img, int new_width, int new_height, t_data *data)
{
	t_img	new_img;
	int		i[2];
	int		orig[2];
	int		color;

	i[Y] = 0;
	new_img = create_new_img(new_width, new_height, data);
	while (i[Y] < HEIGHT)
	{
		i[X] = 0;
		while (i[X] < HEIGHT)
		{
			orig[X] = (i[X] * img->width) / new_width;
			orig[Y] = (i[Y] * img->height) / new_height;
			color = *(int *)(img->addr + (orig[Y] * img->size_line)
					+ (orig[X] * (img->bpp / 8)));
			*(int *)(new_img.addr + (i[Y] * new_img.size_line)
					+ (i[X] * (new_img.bpp / 8))) = color;
			(i[X])++;
		}
		(i[Y])++;
	}
	mlx_destroy_image(data->mlx_data->mlx_ptr, img->ptr);
	*img = new_img;
}
