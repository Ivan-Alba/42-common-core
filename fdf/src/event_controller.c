/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   event_controller.c                                 :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/27 17:29:18 by igarcia2          #+#    #+#             */
/*   Updated: 2024/04/05 19:22:18 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fdf.h"

/*
** @brief  Toggles projection state between Isometric and Orthographic mode.
** @param  vars: Pointer to main environment structure.
** @param  keycode: Identifier of the pressed key (KEY_SPACE).
*/
void	toogle_projection(t_vars *vars, int keycode)
{
	if (keycode == KEY_SPACE)
	{
		vars->is_iso = !(vars->is_iso);
		set_points_values(vars->map, vars);
		center_render(vars);
		refresh_render(vars);
	}
}

/*
** @brief  Translates rendered image with dynamic speed and boundary limits.
** @param  vars: Pointer to main environment structure.
** @param  keycode: Identifier of the pressed directional key (W/A/S/D).
*/
void	move_img(t_vars *vars, int keycode)
{
	int	b[4];
	int	step;

	step = 10 + (vars->scale / 2);
	get_map_bounds(vars->map, b);
	if (keycode == KEY_A && (b[1] + vars->pos_x) - step > 100)
		vars->pos_x -= step;
	else if (keycode == KEY_D && (b[0] + vars->pos_x) + step < WIN_X - 100)
		vars->pos_x += step;
	else if (keycode == KEY_S && (b[2] + vars->pos_y) + step < WIN_Y - 100)
		vars->pos_y += step;
	else if (keycode == KEY_W && (b[3] + vars->pos_y) - step > 100)
		vars->pos_y -= step;
	refresh_render(vars);
}

/*
** @brief  Rotates map horizontally around central axis in 5-degree steps.
** @param  vars: Pointer to main environment structure.
** @param  keycode: Identifier of the pressed key (KEY_Q or KEY_E).
*/
void	rotate_horizontal(t_vars *vars, int keycode)
{
	if (keycode == KEY_Q)
	{
		vars->rotation_angle -= 5;
		if (vars->rotation_angle < 0)
			vars->rotation_angle += 360;
	}
	else if (keycode == KEY_E)
	{
		vars->rotation_angle += 5;
		if (vars->rotation_angle >= 360)
			vars->rotation_angle -= 360;
	}
	set_points_values(vars->map, vars);
	refresh_render(vars);
}

/*
** @brief  Increases or decreases rendering zoom scale within safe bounds.
** @param  vars: Pointer to main environment structure.
** @param  is_plus: Flag indicating zoom in (1) or zoom out (0).
*/
void	change_scale(t_vars *vars, int is_plus)
{
	if (is_plus && vars->scale < 500)
		vars->scale += 1;
	else if (!is_plus && vars->scale > 1)
		vars->scale -= 1;
	set_points_values(vars->map, vars);
	refresh_render(vars);
}

/*
** @brief  Modifies Z-height altitude of map points and updates projection.
** @param  vars: Pointer to main environment structure.
** @param  keycode: Identifier of key controlling height modification.
*/
void	modify_z(t_vars *vars, int keycode)
{
	int	i;
	int	change;

	i = 0;
	change = 1;
	if (keycode == KEY_Z)
		change = -1;
	while (i < vars->map->height * vars->map->width)
	{
		if (!change && (vars->map)->points[i].z > 7)
			(vars->map)->points[i].z += change;
		else if (change && (vars->map)->points[i].z >= 5)
			(vars->map)->points[i].z += change;
		get_iso_values(&(vars->map->points[i]), vars);
		i++;
	}
	refresh_render(vars);
}
