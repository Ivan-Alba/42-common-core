/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   menu.c                                             :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/04/03 18:38:21 by igarcia2          #+#    #+#             */
/*   Updated: 2024/04/05 19:19:28 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fdf.h"

#define CONTROL_BOX 300
#define DRAWINFO_BOX 40
#define LINE_SIZE 30
#define MENU_TAB 30

/*
** @brief  Renders control instructions on the on-screen menu overlay.
** @param  vars: Pointer to main environment state.
*/
static void	draw_controls(t_vars *vars)
{
	int	line;

	line = CONTROL_BOX;
	print_str(vars, MENU_TAB, line, "//// CONTROLS  ////");
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "ZOOM IN: [+]");
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "ZOOM OUT: [-]");
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "MOVE UP: [W]");
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "MOVE DOWN: [S]");
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "MOVE LEFT: [A]");
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "MOVE RIGHT: [D]");
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "ROTATE +: [E]");
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "ROTATE -: [Q]");
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "ORTHOGRAPHIC VIEW: [SPACE]");
}

/*
** @brief  Renders live map metrics (coordinates, Z angle, zoom) on screen.
** @param  vars: Pointer to main environment state.
*/
static void	draw_info(t_vars *vars)
{
	int	line;

	line = DRAWINFO_BOX;
	print_str(vars, MENU_TAB, line, "//// DRAW INFO ////");
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "Pos X:");
	print_nbr(vars, MENU_TAB + 100, line, vars->pos_x);
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "Pos Y:");
	print_nbr(vars, MENU_TAB + 100, line, vars->pos_y);
	line += LINE_SIZE;
	print_str (vars, MENU_TAB, line, "Rotation Y:");
	print_nbr(vars, MENU_TAB + 100, line, vars->rotation_angle);
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "Zoom:");
	print_nbr(vars, MENU_TAB + 100, line, (vars->scale * 100)
		/ vars->initial_scale);
	line += LINE_SIZE;
	print_str(vars, MENU_TAB, line, "View:");
	if (vars->is_iso)
		print_str(vars, MENU_TAB + 100, line, "Isometric");
	else
		print_str(vars, MENU_TAB + 100, line, "Ortographic");
}

/*
** @brief  Master call to draw both info metrics and controls overlay.
** @param  vars: Pointer to main environment state.
*/
void	draw_menu(t_vars *vars)
{
	draw_info(vars);
	draw_controls(vars);
}
