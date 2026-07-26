/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   map_info.c                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/03/23 02:21:35 by igarcia2          #+#    #+#             */
/*   Updated: 2024/04/09 21:33:01 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fdf.h"

/*
** @brief  Calculates 2D projected coordinates based on rotation and ISO mode.
** @param  pnt: Pointer to the point structure to transform.
** @param  vars: Pointer to main environment structure.
*/
void	get_iso_values(t_points *pnt, t_vars *vars)
{
	float	rad;
	int		rot_x;
	int		rot_y;
	int		c_x;
	int		c_y;

	rad = vars->rotation_angle * M_PI / 180.0;
	c_x = (vars->map->width * vars->scale) / 2;
	c_y = (vars->map->height * vars->scale) / 2;
	rot_x = c_x + (pnt->x - c_x) * cos(rad) - (pnt->y - c_y) * sin(rad);
	rot_y = c_y + (pnt->x - c_x) * sin(rad) + (pnt->y - c_y) * cos(rad);
	if (vars->is_iso)
	{
		pnt->x_iso = (rot_x - rot_y) * cos(INIT_Z_ANGLE * M_PI / 180.0);
		pnt->y_iso = (rot_x + rot_y) * sin(INIT_Z_ANGLE * M_PI / 180.0)
			- pnt->z;
	}
	else
	{
		pnt->x_iso = rot_x;
		pnt->y_iso = rot_y;
	}
}

/*
** @brief  Determines vertex color based on hexadecimal string or Z altitude.
** @param  str: String representation of vertex data.
** @param  z: Altitude value of the vertex.
** @return Integer representing the RGB color value.
*/
int	set_color(char *str, int z)
{
	int		color;
	int		i;
	char	*hexa;

	color = 0xFFFFFF;
	if (ft_strchr(str, ','))
	{
		i = 0;
		while (str[i] != ',')
			i++;
		hexa = ft_substr(str, i + 1, 8);
		color = hex_to_int(hexa);
		free(hexa);
	}
	else if (z > 8)
		color = 0xFF2D00;
	return (color);
}

/*
** @brief  Parses string map grid into structured 3D point array with scaled Z.
** @param  map: Pointer to map structure.
** @param  vars: Pointer to main environment structure.
*/
void	set_points_values(t_map *map, t_vars *vars)
{
	int		i;
	int		j;
	int		h;
	char	**line;

	i = 0;
	h = 0;
	while (map->map[i] != NULL)
	{
		j = -1;
		line = ft_split(map->map[i], ' ');
		if (!line)
			exit_error("ERROR");
		while (line[++j] != NULL)
		{
			map->points[h].x = j * vars->scale;
			map->points[h].y = i * vars->scale;
			map->points[h].z = ft_atoi(line[j]) * (vars->scale / 2.0);
			map->points[h].color = set_color(line[j], ft_atoi(line[j]));
			get_iso_values(&(map->points[h]), vars);
			h++;
		}
		i++;
		free_split(line);
	}
}

/*
** @brief  Sets default scale factor, Z angle, and initial camera coordinates.
** @param  vars: Pointer to main environment structure.
*/
void	initialize_settings(t_vars *vars)
{
	t_map	*map;
	int		max_dim;

	map = vars->map;
	if (map->width > map->height)
		max_dim = map->width;
	else
		max_dim = map->height;
	vars->scale = (WIN_X / 3) / max_dim;
	if (vars->scale < 1)
		vars->scale = 1;
	vars->initial_scale = vars->scale;
	vars->pos_x = WIN_X / 2;
	vars->pos_y = WIN_Y / 2;
	vars->is_iso = 1;
	vars->rotation_angle = 0;
}

/*
** @brief  Calculates X and Y offsets to visually center map rendering.
** @param  vars: Pointer to main environment structure.
*/
void	center_render(t_vars *vars)
{
	int	bounds[4];

	get_map_bounds(vars->map, bounds);
	vars->pos_x = (WIN_X / 2) - ((bounds[0] + bounds[1]) / 2);
	vars->pos_y = (WIN_Y / 2) - ((bounds[2] + bounds[3]) / 2);
	refresh_render(vars);
}
