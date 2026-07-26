/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   main.c                                             :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/24 17:05:05 by igarcia2          #+#    #+#             */
/*   Updated: 2024/04/09 20:41:26 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fdf.h"

/*
** @brief  Listens for keypress events and dispatches to control handlers.
** @param  keycode: Identifier of the pressed key.
** @param  vars: Pointer to main environment structure.
** @return Always returns 0.
*/
int	key_pressed(int keycode, t_vars *vars)
{
	ft_printf("KEY PRESSED:%d\n", keycode);
	if (keycode == KEY_ESC)
		close_win(vars);
	else if (keycode == KEY_PLUS)
		change_scale(vars, 1);
	else if (keycode == KEY_MINUS)
		change_scale(vars, 0);
	else if (keycode == KEY_A || keycode == KEY_S || keycode == KEY_D
		|| keycode == KEY_W)
		move_img(vars, keycode);
	else if (keycode == KEY_SPACE)
		toogle_projection(vars, keycode);
	else if (keycode == KEY_Q || keycode == KEY_E)
		rotate_horizontal(vars, keycode);
	else if (keycode == KEY_Z || keycode == KEY_X)
		modify_z(vars, keycode);
	return (0);
}

/*
** @brief  Re-renders image buffer and redraws overlay menu on screen.
** @param  vars: Pointer to main environment structure.
*/
void	refresh_render(t_vars *vars)
{
	t_data	img;

	img.img = mlx_new_image(vars->mlx, WIN_X, WIN_Y);
	img.addr = mlx_get_data_addr(img.img, &img.bits_per_pixel,
			&img.line_length, &img.endian);
	print_pixels(&img, vars);
	mlx_put_image_to_window(vars->mlx, vars->win, img.img, 0, 0);
	draw_menu(vars);
	mlx_loop(vars->mlx);
}

/*
** @brief  Computes grid dimensions and allocates point array memory.
** @param  map: Pointer to map structure.
** @param  vars: Pointer to main environment structure.
*/
void	initialize_map_info(t_map *map, t_vars *vars)
{
	int		i;
	char	**line;

	i = 0;
	while (map->map[i])
		i++;
	map->height = i;
	i = 0;
	line = ft_split(map->map[i], ' ');
	if (!line)
		exit_error("ERROR");
	while (line[i])
		i++;
	map->width = i;
	map->points = malloc((map->width * map->height) * sizeof(t_points));
	if (!map->points)
		exit_error("ERROR");
	free_split(line);
	vars->map = map;
	initialize_settings(vars);
	set_points_values(map, vars);
}

/*
** @brief  Main entry point. Validates args, builds map, and starts MLX loop.
** @param  argc: Total command line argument count.
** @param  argv: Command line argument vector.
** @return Always returns 0 on success.
*/
int	main(int argc, char *argv[])
{
	t_map	map;
	t_vars	vars;

	if (argc != 2)
		exit_error(ARGS_ERROR);
	map.map = read_map(argv[1]);
	initialize_map_info(&map, &vars);
	vars.mlx = mlx_init();
	vars.win = mlx_new_window(vars.mlx, WIN_X, WIN_Y, "FdF");
	mlx_hook(vars.win, 2, 1L << 0, key_pressed, &vars);
	mlx_hook(vars.win, 17, 0, close_win, &vars);
	center_render(&vars);
	refresh_render(&vars);
	return (0);
}
