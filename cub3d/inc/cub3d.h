/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   cub3d.h                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/12/19 21:08:03 by mirifern          #+#    #+#             */
/*   Updated: 2025/03/26 21:07:52 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#ifndef CUB3D_H
# define CUB3D_H
/*Libraries to Bass*/
//# include <stdint.h>
//# include <stdbool.h>
//# include "../bass/bass.h"
/******************/
# include <stdio.h>
# include <stdlib.h>
# include <unistd.h>
# include <fcntl.h>
# include <math.h>
# include <stddef.h>
# include <stdbool.h>
# include <sys/time.h>
# include "../src/libft/libft.h"
# include "../minilibx-linux/mlx.h"
# include <X11/Xlib.h>
# include <X11/keysym.h>
# include "game_settings.h"

/*Coordinates*/
typedef enum e_coord
{
	X = 0,
	Y = 1,
	HORZ = 0,
	VERT = 1,
	TRUE = 1,
	FALSE = 0
}	t_coord;

typedef enum e_rgb_matrix
{
	R = 0,
	G = 1,
	B = 2
}	t_rgb_matrix;

typedef enum e_door_state
{
	CLOSED,
	OPENED,
	OPENING,
	CLOSING
}	t_door_state;

/*Player*/
typedef struct s_player
{
	int				pos[2];
	int				coord[2];
	double			angle;
	int				level;
	int				score;
	int				total_chest;
	int				total_coin;
	int				total_chest_found;
	int				total_coin_found;
	int				frame;
	unsigned long	last_frame_time;
	int				exit_reached;
}	t_player;

/*Texture images*/
typedef struct s_texture
{
	void				*img;
	char				*addr;
	int					bpp;
	int					line_len;
	int					endian;
	char				id_txt;
	int					width;
	int					height;
	char				*path;
	struct s_texture	*next;
}	t_texture;

typedef struct s_sprite
{
	int				type;
	int				subtype;
	int				grid[2];
	int				world[2];
	int				size[2];
	int				start[2];
	double			distance;
	int				txt_num;
	int				frame;
	unsigned long	last_frame_time;
	int				visible_horz;
	int				visible_vert;
	struct s_sprite	*next;
}	t_sprite;

/*Images*/
typedef struct s_img
{
	void	*ptr;
	char	*addr;
	int		width;
	int		height;
	int		bpp;
	int		size_line;
	int		endian;
}	t_img;

/*Door data*/
typedef struct s_door
{
	int				grid[2];
	int				orient;
	t_door_state	state;
	float			offset;
	double			timer;
	int				is_locked;
	struct s_door	*next;
}	t_door;

/*Map info*/
typedef struct s_map
{
	char		**map;
	int			max_height;
	int			max_width;
	int			ceiling_color;
	int			floor_color;
	int			floor_tiles_qt;
	int			floor_tiles_found;
	int			exit_qt;
	int			key_qt;
	int			key_found;
	int			chest_qt;
	int			chest_found;
	int			coin_qt;
	int			coin_found;
	char		*next_map;
	t_texture	*txt_list;
	t_door		*door_list;
	t_sprite	*sprite_list;
}	t_map;

/*Raycasting data*/
typedef struct s_raycast
{
	double	distance_pp;
	double	angle_increment;
	double	alpha;
	double	horz_hit[2];
	double	vert_hit[2];
	int		vtx_hit;
	double	shortest_distance;
	double	corrected_distance;
	int		wall_height;
	int		wall_y;
	t_door	*door;
	float	pixel_distance;
	float	intensity;
}	t_raycast;

/*Minimap data*/
typedef struct s_minimap
{
	int		size[2];
	int		start[2];
	int		margin[2];
	int		tile_size;
	int		tiles_horz;
	double	shortest_distances[WIDTH];
	double	angle_step;
	double	scale;
}	t_minimap;

/*Title screen*/
typedef struct s_title
{
	t_img	imgs[TITLE_IMAGES];
	char	*paths[TITLE_IMAGES];
	int		blink;
	int		selected;
}	t_title;

/*Mlx*/
typedef struct s_mlx
{
	void	*mlx_ptr;
	void	*win_ptr;
	void	*img_ptr;
	char	*img_addr;
	char	*new_img_addr;
	int		bpp;
	int		line_len;
	int		endian;
	int		mouse_pos;
	int		keys[6];
	int		game_active;
}	t_mlx;

/*Audio*/
typedef struct s_audio
{
	uint32_t		bass_id;
	int				audio_id;
	struct s_audio	*next;
}	t_audio;

/*Cub3d*/
typedef struct s_data
{
	t_map			*map_data;
	t_raycast		*ray_data;
	t_minimap		*minimap_data;
	t_mlx			*mlx_data;
	t_player		*player;
	t_title			*title_data;
	t_title			*end_data;
	char			**cub_file;
	t_audio			*audio_list;
	double			loop_time;
}	t_data;

/*main.c*/
int			main(int ac, char **av);

/*init.c*/
void		init_data(t_data **data);
void		init_minimap_data(t_minimap *minimap_data);
void		init_map(t_map *map_data);
void		init_mlx(t_data *data);
void		init_textures(t_texture *txt_data, t_mlx *mlx_data,	t_data *data);

/*init_mlx.c*/
void		init_mlx(t_data *data);

/*add_textures.c*/
void		add_common_textures(t_data *data);

/*title_screen.c*/
void		init_title(t_title *title_data);
void		title_screen(t_title *title_data, t_data *data);

/*open_file.c*/
void		check_paths_qt(t_data *data);
void		try_open_path(t_data *data, char *path);
int			read_file(int fd, t_data *data);
int			open_cub_file(char *path, t_data *data);

/*parse_file.c*/
int			check_tiles_between(char **map, int y, int x);
char		*parse_path(char *line, t_data *data, char id);
int			parse_line(t_data *data, char *line);
void		parse_cub_file(t_data *data, char **cub_file);

/*parse_map.c*/
void		normalize_map(t_data *data, char **map);
void		parse_map(t_data *data, char **map_line);

/*validate.c*/
void		validate_extras(t_data *data, char **map);
void		validate_map_border(t_data *data, t_map *map_data, char **map);

/*validate_tiles.c*/
int			check_tiles_between_exit(char **map, int y, int x);
void		validate_exit(t_data *data, char **map, int *grid);
int			check_player(t_data *data, int y, int x);
void		validate_player(t_data *data, char **map);
void		validate_tiles(t_data *data, char **map);

/*validate_txt_and_colors.c*/
void		validate_conf_textures(t_data *data);
int			check_rgb(char *path, int *i);
int			rgb_to_hex(char *path);
int			check_color_or_texture(t_data *data, char *path, char id);
void		check_exit_sprite(char *path, t_data *data, int id);

/*flood_fill.c*/
void		validate_map_route(t_data *data);

//------UTILS------//

/*error.c*/
void		ft_error_exit(char *msg, t_data *data);
void		ft_error(char *msg);
void		ft_perror(char *msg);

/*str_utils.c*/
char		*load_path(int i);
char		**add_to_array(char ***current, char *new_value);

/*free_utils*/
void		free_data(t_data *data);
void		free_str_array(char ***str_array);
void		destroy_mlx(t_data *data);

/*utils.c*/
void		malloc_protection(void *ptr, t_data *data);
int			only_spaces(const char *str);
long		get_time(void);

/*tile_utils.c*/
int			get_door_type(char **map, int y, int x);
int			is_crossable(char tile);

/*free_utils.c*/
void		free_map(t_map *map_data, t_mlx *mlx_data);

/*angle_utils.c*/

double		deg_to_rad(double degrees);
double		normalize_angle(double angle);
int			calculate_angle(char **map, int x, int y);

/*image_utils.c*/
t_img		create_new_img(int width, int height, t_data *data);
void		load_xpm_image(t_img *img, char *path, t_data *data);
void		scale_image(t_img *img, int new_width, int new_height,
				t_data *data);

/*test_utils.c*/
void		print_str_array(char **str_array);
void		init_map_test(t_map *map, t_data *data);
void		print_player_info(t_data *data);
void		print_nbr(t_mlx *mlx_data, int x, int y, int nbr);
void		print_str(t_mlx *mlx_data, int x, int y, char *str);
void		print_ray_data(t_raycast *ray_data);

/*texture_list.c*/
void		add_texture_node(char id_texture, char *path, t_data *data);
void		clear_txt_list(t_texture **txt_list, t_mlx *mlx_data);
t_texture	*get_texture(char id_txt, t_data *data);

/*door_list.c*/
void		add_door_node(int grid[2], int orient, int locked, t_data *data);
void		clear_door_list(t_door **door_list);
t_door		*get_door(int grid[2], t_data *data);

/*audio_list.c*/
void		add_audio_node(char id_audio, uint32_t bass_id, t_data *data);
void		clear_audio_list(t_audio **audio_list);
t_audio		*get_audio(char audio_id, t_data *data);

/*sprite_list.c*/
void		add_sprite_node(int type, int subtype, int grid[2], t_data *data);
void		clear_sprite_list(t_sprite **sprite_list);
t_sprite	*get_sprite(int grid[2], t_data *data);
void		delete_sprite(t_sprite *sprite, t_data *data);

//------RAYCAST------//

/*raycast_manager.c*/
void		raycast_manager(t_raycast *ray_data, t_data *data);

/*hit_wall.c*/
void		vert_hit(double alpha, t_player *player, t_data *data);
void		horz_hit(double alpha, t_player *player, t_data *data);

/*hit_door.c*/
int			door_hit(t_door *door, double hit[2], double delta[2]);
int			is_door_hit(t_raycast *ray_data, t_data *data);

/*hit_sprite.c*/
void		check_sprites_along_ray(t_raycast *ray_data, t_data *data);
void		reset_sprite_visibility(t_map *map_data);
void		set_sprite_visible(int grid[2], int intersection, t_data *data);

/*raycast_utils.c*/
int			get_tile_type(int grid[2], t_map *map_data);
void		get_grid_back_hit(int grid[2], t_raycast *ray_data);
double		calculate_distance(double x1, double y1, double x2, double y2);

//--------RENDER-------//

/*render_wall.c*/
void		render_wall(int x, int *y, t_raycast *ray_data, t_data *data);

/*render_ceil_floor.c*/
void		render_ceil_floor(int x, int *y, t_data *data);

/*render_door.c*/
void		render_door(int x, int *y, t_raycast *ray_data, t_data *data);

/*render_sprite.c*/
void		render_sprite(int x, int y, t_data *data);

/*render_texture.c*/
int			get_texture_pxl(t_texture *texture, int x, int y);

/*render_ui.c*/
void		render_ui(t_data *data);
void		print_num_sprite(int pos[2], int sprite_size, int num,
				t_data *data);
int			get_ui_txt_color(int x, int y, int sprite_size, t_texture *texture);
void		print_ui_score(int pos[2], int sprite_size, t_data *data);

/*render_ui_utils.c*/
int			get_ui_txt_color(int x, int y, int sprite_size, t_texture *texture);
int			get_digit_txt(int num);

/*render_player.c*/
void		render_player(t_data *data);

/*minimap.c*/
void		create_minimap(t_minimap *minimap_data, t_mlx *mlx_data,
				t_data *data);

/*minimap_utils.c*/
void		print_tile_pixel(int x, int y, int map_idx[2], t_data *data);
void		print_triangle(int v[3][2], int color, t_mlx *mlx_data);

/*move_player.c*/
void		move_player(int dir, int angle, double delta_time, t_data *data);
void		check_interactable(t_data *data);

/*update_player.c*/
void		update_player(double delta_time, t_data *data);

/*game_loop.c*/
int			game_loop(t_data *data);

/*update_door.c*/
void		check_doors(t_data *data);
void		update_doors(t_data *data);

/*next_map.c*/
int			load_next_map(t_data *data);

/*mlx_print.c*/
void		print_pixel_render(int x, int y, int color, t_data *data);
void		print_sprite_pxl(int pos[2], int color, t_sprite sprite,
				t_data *data);
void		print_gui_pixel(int x, int y, int color, t_mlx *mlx_data);

/*mlx_input.c*/
int			mouse_handler(int x, int y, t_data *data);
int			key_release(int keycode, t_data *data);
int			key_press(int keycode, t_data *data);
int			close_window(t_data *data);

/*audio.c*/
void		play_sound(char audio_id, bool play, bool loop, t_data *data);
void		stop_audio(char audio_id, t_data *data);
void		init_audio(t_data *data);

/*end_screen.c*/
void		end_screen(t_data *data);

#endif
