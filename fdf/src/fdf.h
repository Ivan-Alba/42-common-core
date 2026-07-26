/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   fdf.h                                              :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/26 12:34:50 by igarcia2          #+#    #+#             */
/*   Updated: 2024/07/17 15:22:49 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#ifndef FDF_H
# define FDF_H

# include "libft/libft.h"
# include "libft/ft_printf/ft_printf.h"
# include "libft/get_next_line/get_next_line.h"
# include <mlx.h>
# include <math.h>
# include <stdint.h>
# include <inttypes.h>
# include <unistd.h>

/*
** Linux / X11 Keycodes
*/
# define KEY_ESC 65307
# define KEY_W 119
# define KEY_A 97
# define KEY_S 115
# define KEY_D 100
# define KEY_PLUS 43
# define KEY_MINUS 45
# define KEY_SPACE 32
# define KEY_Q 113
# define KEY_E 101
# define KEY_Z 122
# define KEY_X 120

/*
** Error messages & default parameters
*/
# define ARGS_ERROR "Args error\n"
# define FILE_ERROR "File error\n"
# define MAP_ERROR "Map format error\n"
# define SPLIT_ERROR "Split error\n"
# define WIN_X 1920
# define WIN_Y 1080
# define INIT_SCALE 30
# define INIT_Z_ANGLE 20
# define MOVE_QTY 5
# define TEXT_COLOR 0xFFFFFF
# define NUMBER_COLOR 0x8fce00

/*
** @struct s_data
** @brief  Holds MiniLibX image buffer details and pixel addressing.
*/
typedef struct s_data
{
	void	*img;
	char	*addr;
	int		bits_per_pixel;
	int		line_length;
	int		endian;
}	t_data;

/*
** @struct s_points
** @brief  Represents a single map point in 3D and projected 2D space.
*/
typedef struct s_points
{
	int		x;
	int		y;
	int		z;
	int		x_iso;
	int		y_iso;
	int		color;
}	t_points;

/*
** @struct s_map
** @brief  Stores the raw text map grid, dimensions, and parsed 3D points.
*/
typedef struct s_map
{
	char		**map;
	int			width;
	int			height;
	t_points	*points;
}	t_map;

/*
** @struct s_vars
** @brief  Main environment state structure containing MLX pointers and
**         view options.
*/
typedef struct s_vars
{
	void	*mlx;
	void	*win;
	int		scale;
	int		initial_scale;
	int		is_iso;
	int		rotation_angle;
	int		pos_x;
	int		pos_y;
	t_map	*map;
}	t_vars;

/*
** -----------------------------------------------------------------------------
**                            INITIALIZATION & PARSING
** -----------------------------------------------------------------------------
*/

char	**read_map(char *file);
void	initialize_map_info(t_map *map, t_vars *vars);
void	initialize_settings(t_vars *vars);
void	set_points_values(t_map *map, t_vars *vars);

/*
** -----------------------------------------------------------------------------
**                            RENDERING & PROJECTION
** -----------------------------------------------------------------------------
*/

void	print_pixels(t_data *img, t_vars *vars);
void	convert_isometric(int *x, int *y, int z);
void	get_iso_values(t_points *pnt, t_vars *vars);
void	refresh_render(t_vars *vars);
void	center_render(t_vars *vars);
void	draw_menu(t_vars *vars);
void	get_map_bounds(t_map *map, int bounds[4]);
int		set_color(char *str, int z);

/*
** -----------------------------------------------------------------------------
**                            EVENT CONTROLLER
** -----------------------------------------------------------------------------
*/

int		key_pressed(int keycode, t_vars *vars);
int		close_win(t_vars *vars);
void	change_scale(t_vars *vars, int is_plus);
void	move_img(t_vars *vars, int keycode);
void	toogle_projection(t_vars *vars, int keycode);
void	rotate_horizontal(t_vars *vars, int keycode);
void	rotate_z_img(t_vars *vars, int keycode);
void	modify_z(t_vars *vars, int keycode);

/*
** -----------------------------------------------------------------------------
**                            UTILITIES & CLEANUP
** -----------------------------------------------------------------------------
*/

void	exit_error(char *error_msg);
int		round_float(float num);
void	print_nbr(t_vars *vars, int x, int y, int nbr);
void	print_str(t_vars *vars, int x, int y, char *str);
void	free_split(char **str);
int		hex_to_int(char *hex);

#endif
