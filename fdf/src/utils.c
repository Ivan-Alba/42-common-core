/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   utils.c                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/26 13:14:20 by igarcia2          #+#    #+#             */
/*   Updated: 2024/04/05 18:55:02 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fdf.h"

/*
** @brief  Prints an error message to stdout and exits the program.
** @param  error_msg: String error message to display.
*/
void	exit_error(char *error_msg)
{
	ft_printf("%s", error_msg);
	exit(0);
}

/*
** @brief  Converts an integer to a string and draws it onto the window.
** @param  vars: Pointer to main environment structure.
** @param  x: Horizontal screen coordinate.
** @param  y: Vertical screen coordinate.
** @param  nbr: Integer number to draw.
*/
void	print_nbr(t_vars *vars, int x, int y, int nbr)
{
	char	*str;

	str = ft_itoa(nbr);
	mlx_string_put(vars->mlx, vars->win, x, y, NUMBER_COLOR, str);
	free(str);
}

/*
** @brief  Draws a text string onto the MiniLibX window interface.
** @param  vars: Pointer to main environment structure.
** @param  x: Horizontal screen coordinate.
** @param  y: Vertical screen coordinate.
** @param  str: String to draw.
*/
void	print_str(t_vars *vars, int x, int y, char *str)
{
	mlx_string_put(vars->mlx, vars->win, x, y, TEXT_COLOR, str);
}

/*
** @brief  Converts a hexadecimal string representation into an integer color.
** @param  hex: String containing hexadecimal value (with or without '0x').
** @return Integer representation of the hexadecimal value.
*/
int	hex_to_int(char *hex)
{
	int		res;
	int		i;
	char	c;
	int		decimal;

	i = 0;
	res = 0;
	if (hex[0] == '0' && (hex[1] == 'x' || hex[1] == 'X'))
		i = 2;
	while (hex[i] != '\0')
	{
		c = hex[i];
		if (c >= '0' && c <= '9')
			decimal = c - '0';
		else if (c >= 'a' && c <= 'f')
			decimal = 10 + c - 'a';
		else if (c >= 'A' && c <= 'F')
			decimal = 10 + c - 'A';
		res = res * 16 + decimal;
		i++;
	}
	return (res);
}

/*
** @brief  Finds the minimum and maximum projected boundaries of the map.
** @param  map: Pointer to map structure.
** @param  bounds: Array of 4 integers [min_x, max_x, min_y, max_y].
*/
void	get_map_bounds(t_map *map, int bounds[4])
{
	int	i;

	i = 0;
	bounds[0] = WIN_X;
	bounds[1] = -WIN_X;
	bounds[2] = WIN_Y;
	bounds[3] = -WIN_Y;
	while (i < map->width * map->height)
	{
		if (map->points[i].x_iso < bounds[0])
			bounds[0] = map->points[i].x_iso;
		if (map->points[i].x_iso > bounds[1])
			bounds[1] = map->points[i].x_iso;
		if (map->points[i].y_iso < bounds[2])
			bounds[2] = map->points[i].y_iso;
		if (map->points[i].y_iso > bounds[3])
			bounds[3] = map->points[i].y_iso;
		i++;
	}
}
