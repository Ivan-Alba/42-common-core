/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   sprite.h                                           :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/04 20:58:02 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 20:58:29 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#ifndef SPRITE_H
# define SPRITE_H

# define COIN_TXT_PATH "./assets/sprites/coin.xpm"
# define KEY_TXT_PATH "./assets/sprites/key.xpm"
# define CHEST_TXT_PATH "./assets/sprites/chest.xpm"

typedef enum e_sprite_type
{
	COLLECTABLE,
	DECORATION,
	PORTAL,
	ENEMY
}	t_sprite_type;

typedef enum e_decoration_type
{
	T_TREE = 300
}	t_decoration_type;

typedef enum e_collectable_type
{
	T_CHEST = 200,
	T_COIN = 201,
	T_KEY = 202
}	t_collectable_type;

#endif
