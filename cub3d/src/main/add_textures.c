/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   add_textures.c                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/25 18:19:40 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:54:48 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/texture.h"
#include "../../inc/ui.h"
#include "../../inc/sprite.h"

void	add_ui_textures(t_data *data)
{
	add_texture_node(ID_LEVEL_UI, ft_strdup(LEVEL_UI_TXT_PATH), data);
	add_texture_node(ID_PLAYER_UI, ft_strdup(PLAYER_UI_PATH), data);
	add_texture_node(ID_CHEST_UI, ft_strdup(CHEST_UI_TXT_PATH), data);
	add_texture_node(ID_COIN_UI, ft_strdup(COIN_UI_TXT_PATH), data);
	add_texture_node(ID_SCORE_UI, ft_strdup(SCORE_UI_TXT_PATH), data);
	add_texture_node(ID_KEY_UI, ft_strdup(KEY_UI_TXT_PATH), data);
	add_texture_node(ID_KEY_SHADOW_UI, ft_strdup(KEY_SHADOW_UI_TXT_PATH), data);
	add_texture_node(ID_DIGIT_0_UI, ft_strdup(UI_0_TXT_PATH), data);
	add_texture_node(ID_DIGIT_1_UI, ft_strdup(UI_1_TXT_PATH), data);
	add_texture_node(ID_DIGIT_2_UI, ft_strdup(UI_2_TXT_PATH), data);
	add_texture_node(ID_DIGIT_3_UI, ft_strdup(UI_3_TXT_PATH), data);
	add_texture_node(ID_DIGIT_4_UI, ft_strdup(UI_4_TXT_PATH), data);
	add_texture_node(ID_DIGIT_5_UI, ft_strdup(UI_5_TXT_PATH), data);
	add_texture_node(ID_DIGIT_6_UI, ft_strdup(UI_6_TXT_PATH), data);
	add_texture_node(ID_DIGIT_7_UI, ft_strdup(UI_7_TXT_PATH), data);
	add_texture_node(ID_DIGIT_8_UI, ft_strdup(UI_8_TXT_PATH), data);
	add_texture_node(ID_DIGIT_9_UI, ft_strdup(UI_9_TXT_PATH), data);
	add_texture_node(ID_BAR_UI, ft_strdup(UI_BAR_TXT_PATH), data);
}

void	add_collectable_textures(t_data *data)
{
	add_texture_node(ID_COIN, ft_strdup(COIN_TXT_PATH), data);
	add_texture_node(ID_CHEST, ft_strdup(CHEST_TXT_PATH), data);
	add_texture_node(ID_KEY, ft_strdup(KEY_TXT_PATH), data);
}

void	add_common_textures(t_data *data)
{
	add_ui_textures(data);
	add_collectable_textures(data);
}
