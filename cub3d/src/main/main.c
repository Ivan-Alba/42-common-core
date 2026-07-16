/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   main.c                                             :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/12/19 21:05:41 by mirifern          #+#    #+#             */
/*   Updated: 2025/02/18 22:53:35 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/error.h"

// Check if arg value is valid
int	check_args(int ac, char **av)
{
	if (ac != 2)
		return (ft_error(ERR_ARGS), EXIT_FAILURE);
	if (ft_strncmp(av[1] + ft_strlen(av[1]) - 4, ".cub", 4))
		return (ft_error(ERR_EXT), EXIT_FAILURE);
	return (EXIT_SUCCESS);
}

// Main function
int	main(int ac, char **av)
{
	t_data	*data;

	data = NULL;
	if (check_args(ac, av) == EXIT_FAILURE)
		return (EXIT_FAILURE);
	init_data(&data);
	if (open_cub_file(av[1], data) == EXIT_FAILURE)
		return (free_data(data), EXIT_FAILURE);
	init_mlx(data);
	init_audio(data);
	title_screen(data->title_data, data);
	add_common_textures(data);
	init_textures(data->map_data->txt_list, data->mlx_data, data);
	mlx_loop(data->mlx_data->mlx_ptr);
	return (0);
}
