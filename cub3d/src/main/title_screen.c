/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   title_screen.c                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/18 22:17:27 by mirifern          #+#    #+#             */
/*   Updated: 2025/02/18 22:17:29 by mirifern         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/title_end.h"
#include "../../inc/audio.h"
#include "../../inc/error.h"
#include "../../inc/key.h"

void	init_title(t_title *title_data)
{
	int	i;

	i = 0;
	while (i < TITLE_IMAGES)
	{
		title_data->imgs[i].ptr = NULL;
		title_data->imgs[i].addr = NULL;
		i++;
	}
	title_data->paths[0] = TITLE_IMG_PATH_0;
	title_data->paths[1] = TITLE_IMG_PATH_1;
	title_data->paths[2] = TITLE_IMG_PATH_2;
	title_data->paths[3] = TITLE_IMG_PATH_3;
	title_data->paths[4] = TITLE_IMG_PATH_4;
	title_data->paths[5] = TITLE_IMG_PATH_5;
	title_data->paths[6] = TITLE_IMG_PATH_6;
	title_data->paths[7] = TITLE_IMG_PATH_7;
}

int	key_title(int keycode, t_data *data)
{
	if (keycode == KEY_LEFT)
		data->title_data->selected = 7;
	if (keycode == KEY_RIGHT)
		data->title_data->selected = 6;
	if (keycode == KEY_SPACE && data->title_data->selected == 6)
	{
		stop_audio(TITLE_AUDIO, data);
		data->mlx_data->game_active = 1;
		mlx_hook(data->mlx_data->win_ptr, 2, 1L << 0, key_press, data);
		mlx_loop_hook(data->mlx_data->mlx_ptr, game_loop, data);
		mlx_mouse_move(data->mlx_data->mlx_ptr, data->mlx_data->win_ptr,
			WIDTH / 2, HEIGHT / 2);
		mlx_mouse_hide(data->mlx_data->mlx_ptr, data->mlx_data->win_ptr);
		mlx_hook(data->mlx_data->win_ptr, 6, 1L << 6, mouse_handler, data);
		play_sound(SPRING_AUDIO, true, true, data);
	}
	else if ((keycode == KEY_SPACE && data->title_data->selected == 7)
		|| keycode == KEY_ESC)
	{
		free_data(data);
		exit(EXIT_SUCCESS);
	}
	return (0);
}

int	title_loop(t_data *data)
{
	int		index;
	t_mlx	*info;

	info = data->mlx_data;
	if (data->title_data->blink)
		index = data->title_data->selected;
	else
		index = 5;
	if (index < 5 || index >= 9 || !data->title_data->imgs[index].ptr)
	{
		ft_error(ERR_START);
		return (0);
	}
	mlx_put_image_to_window(info->mlx_ptr, info->win_ptr,
		data->title_data->imgs[index].ptr, WIDTH / 2 - HEIGHT / 2, 0);
	usleep(160000);
	data->title_data->blink = !data->title_data->blink;
	return (0);
}

void	render_fade_in(t_data *data)
{
	int		i;
	t_mlx	*info;

	i = 0;
	info = data->mlx_data;
	while (i < 6)
	{
		mlx_put_image_to_window(info->mlx_ptr, info->win_ptr,
			data->title_data->imgs[i].ptr, WIDTH / 2 - HEIGHT / 2, 0);
		usleep(80000);
		i++;
	}
}

void	title_screen(t_title *title_data, t_data *data)
{
	int			i;

	i = -1;
	while (++i < TITLE_IMAGES)
	{
		load_xpm_image(&(title_data->imgs[i]), title_data->paths[i], data);
		scale_image(&(title_data->imgs[i]), HEIGHT, HEIGHT, data);
	}
	data->title_data->selected = 6;
	render_fade_in(data);
	play_sound(TITLE_AUDIO, true, true, data);
	mlx_loop_hook(data->mlx_data->mlx_ptr, title_loop, data);
	mlx_hook(data->mlx_data->win_ptr, 2, 1L << 0, key_title, data);
	mlx_hook(data->mlx_data->win_ptr, 3, 1L << 1, key_release, data);
}
