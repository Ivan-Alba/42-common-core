/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   init.c                                             :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/02 02:27:49 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:44:15 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/error.h"

void	check_res(t_mlx *mlx_data, t_data *data)
{
	int	screen_width;
	int	screen_height;

	mlx_get_screen_size(mlx_data->mlx_ptr, &screen_width, &screen_height);
	if (HEIGHT < 100 || WIDTH < 100)
		ft_error_exit(ERR_WINDOW_SIZE, data);
	if (HEIGHT > WIDTH)
		ft_error_exit(ERR_HEIGHT, data);
	if (WIDTH > screen_width || HEIGHT > screen_height)
		ft_error_exit(ERR_MAX_RES, data);
}

void	init_mlx(t_data *data)
{
	data->mlx_data = malloc(sizeof(t_mlx));
	malloc_protection(data->mlx_data, data);
	data->mlx_data->img_ptr = NULL;
	data->mlx_data->img_addr = NULL;
	data->mlx_data->win_ptr = NULL;
	data->mlx_data->new_img_addr = NULL;
	data->mlx_data->img_ptr = NULL;
	data->mlx_data->img_addr = NULL;
	data->mlx_data->mouse_pos = 0;
	data->mlx_data->game_active = 0;
	data->mlx_data->mlx_ptr = mlx_init();
	if (!data->mlx_data->mlx_ptr)
		ft_error_exit(ERR_MLX_INIT, data);
	check_res(data->mlx_data, data);
	data->mlx_data->win_ptr
		= mlx_new_window(data->mlx_data->mlx_ptr, WIDTH, HEIGHT, "cub3d");
	if (!data->mlx_data->win_ptr)
		ft_error_exit(ERR_MLX_WIN, data);
	ft_memset(data->mlx_data->keys, 0, sizeof(data->mlx_data->keys));
	mlx_hook(data->mlx_data->win_ptr, 17, 0, close_window, data);
	mlx_hook(data->mlx_data->win_ptr, 6, 1L << 6, mouse_handler, data);
}
