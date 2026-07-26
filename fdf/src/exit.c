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
** @brief  Destroys window, frees map structure memory, and exits cleanly.
** @param  vars: Pointer to main environment structure.
** @return Always returns 0.
*/
int	close_win(t_vars *vars)
{
	mlx_destroy_window(vars->mlx, vars->win);
	free_split((vars->map)->map);
	free((vars->map)->points);
	exit(0);
}

/*
** @brief  Frees a NULL-terminated array of dynamically allocated strings.
** @param  str: Double pointer to array of strings.
*/
void	free_split(char **str)
{
	int	i;

	i = 0;
	while (str[i])
		free(str[i++]);
	free(str);
}
