/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   render_ui.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/02 02:27:49 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:44:15 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/ui.h"

int	get_ui_txt_color(int x, int y, int sprite_size, t_texture *texture)
{
	int			color;
	double		txt[2];

	color = 0x000000;
	if (!texture)
		return (color);
	txt[X] = x / ((double)sprite_size * (texture->width / texture->height));
	txt[X] = txt[X] * texture->width;
	txt[Y] = y / (double)sprite_size * texture->height;
	txt[X] = fmod(txt[X], texture->width);
	txt[Y] = fmod(txt[Y], texture->height);
	color = get_texture_pxl(texture, txt[X], txt[Y]);
	return (color);
}

int	get_digit_txt(int num)
{
	if (num == 0)
		return (ID_DIGIT_0_UI);
	else if (num == 1)
		return (ID_DIGIT_1_UI);
	else if (num == 2)
		return (ID_DIGIT_2_UI);
	else if (num == 3)
		return (ID_DIGIT_3_UI);
	else if (num == 4)
		return (ID_DIGIT_4_UI);
	else if (num == 5)
		return (ID_DIGIT_5_UI);
	else if (num == 6)
		return (ID_DIGIT_6_UI);
	else if (num == 7)
		return (ID_DIGIT_7_UI);
	else if (num == 8)
		return (ID_DIGIT_8_UI);
	else if (num == 9)
		return (ID_DIGIT_9_UI);
	return (0);
}
