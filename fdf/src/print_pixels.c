/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   print_pixels.c                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/27 17:38:19 by igarcia2          #+#    #+#             */
/*   Updated: 2024/07/17 15:24:55 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fdf.h"

/*
** @brief  Calculates color interpolation between two points for gradient lines.
** @param  point1: Starting point containing initial RGB color.
** @param  point2: Ending point containing target RGB color.
** @param  i: Current step index along the line.
** @param  max: Total number of steps/pixels in the line segment.
** @return Interpolated RGB color value.
*/
int	get_color(t_points point1, t_points point2, int i, int max)
{
	float	red_inc;
	float	green_inc;
	float	blue_inc;
	int		color;

	red_inc = (float)(((point2.color >> 16) & 0xFF)
			- ((point1.color >> 16) & 0xFF)) / max;
	green_inc = (float)(((point2.color >> 8) & 0xFF)
			- ((point1.color >> 8) & 0xFF)) / max;
	blue_inc = (float)((point2.color & 0xFF)
			- (point1.color & 0xFF)) / max;
	color = (((point1.color >> 16) & (0xFF + (int)(red_inc * i))) << 16)
		| (((point1.color >> 8) & (0xFF + (int)(green_inc * i))) << 8)
		| ((point1.color & 0xFF) + (int)(blue_inc * i));
	return (color);
}

/*
** @brief  Puts a single pixel of specified color into the image buffer.
** @param  data: Pointer to image buffer structure.
** @param  x: X coordinate on screen.
** @param  y: Y coordinate on screen.
** @param  color: RGB color value to write.
*/
void	putpixel(t_data *data, int x, int y, int color)
{
	char	*dst;

	if (x >= 0 && y >= 0 && x < WIN_X && y < WIN_Y)
	{
		dst = data->addr + (y * data->line_length
				+ x * (data->bits_per_pixel / 8));
		*(unsigned int *)dst = color;
	}
}

/*
** @brief  Rounds a floating-point number to the nearest integer.
** @param  num: Floating-point value to round.
** @return Rounded integer value.
*/
int	round_float(float num)
{
	if (num - (int)num >= 0.5)
		return ((int)num + 1);
	return ((int)num);
}

/*
** @brief  Draws a line between two projected 2D points using DDA algorithm.
** @param  data: Pointer to image buffer structure.
** @param  pnt1: Starting point structure.
** @param  pnt2: Ending point structure.
** @param  vars: Pointer to main environment state.
*/
void	print_lines(t_data *data, t_points pnt1, t_points pnt2, t_vars *vars)
{
	int		max;
	float	x;
	float	y;
	int		i;

	if (abs(pnt2.x_iso - pnt1.x_iso) > abs(pnt2.y_iso - pnt1.y_iso))
		max = abs(pnt2.x_iso - pnt1.x_iso);
	else
		max = abs(pnt2.y_iso - pnt1.y_iso);
	if (max > 0)
	{
		x = (float)pnt1.x_iso + ((pnt2.x_iso - pnt1.x_iso) / (float)max);
		y = (float)pnt1.y_iso + ((pnt2.y_iso - pnt1.y_iso) / (float)max);
		i = 0;
		while (i < max)
		{
			putpixel(data, round_float(x) + vars->pos_x, round_float(y)
				+ vars->pos_y, get_color(pnt1, pnt2, i, max));
			x = x + ((pnt2.x_iso - pnt1.x_iso) / (float)max);
			y = y + ((pnt2.y_iso - pnt1.y_iso) / (float)max);
			i++;
		}
	}
}

/*
** @brief  Iterates through map points and draws grid points and
**         connecting lines.
** @param  data: Pointer to image buffer structure.
** @param  vars: Pointer to main environment state.
*/
void	print_pixels(t_data *data, t_vars *vars)
{
	int		i;
	t_map	*map;

	map = vars->map;
	i = 0;
	while (i < map->width * map->height)
	{
		putpixel(data, map->points[i].x_iso + vars->pos_x,
			map->points[i].y_iso + vars->pos_y, map->points[i].color);
		if ((i + 1) < (map->width * map->height) && (i + 1) % map->width != 0)
			print_lines(data, map->points[i], map->points[i + 1], vars);
		if (i + map->width < map->width * map->height)
			print_lines(data, map->points[i],
				map->points[i + map->width], vars);
		i++;
	}
}
