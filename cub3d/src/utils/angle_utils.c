/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   angle_utils.c                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/20 15:27:13 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/02 21:14:20 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"

// Function to convert degrees to radians
double	deg_to_rad(double degrees)
{
	return (degrees * M_PI / 180.0);
}

//Transform angles on 0-360 range
double	normalize_angle(double angle)
{
	angle = fmod(angle, 360);
	if (angle < 0)
		angle += 360;
	return (angle);
}

//Gets player start position angle
int	calculate_angle(char **map, int y, int x)
{
	if (map[y][x] == 'N')
		return (90);
	else if (map[y][x] == 'S')
		return (270);
	else if (map[y][x] == 'E')
		return (0);
	else if (map[y][x] == 'W')
		return (180);
	return (-1);
}
