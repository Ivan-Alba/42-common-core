/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   tile.h                                             :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/04 20:58:02 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 20:58:29 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#ifndef TILE_H
# define TILE_H

# if BONUS == 1
#  define VALID_TILES "01NSWEDXQKCLT "
# else
#  define VALID_TILES "01NSWE "
# endif

# define SPRITE_TILES "XQKCT"
# define INTERACTABLE_TILES "CKQX"
# define COLLECTABLE_TILES "CQK"
# define DOOR_TILES "DL"
# define CROSSABLE_TILES "0DNSEWXLQCK"

typedef enum e_tile_type
{
	TILE_WALL = '1',
	TILE_FLOOR = '0',
	TILE_SPACE = ' ',
	TILE_N = 'N',
	TILE_E = 'E',
	TILE_W = 'W',
	TILE_S = 'S',
	TILE_DOOR = 'D',
	TILE_LOCKED_DOOR = 'L',
	TILE_EXIT = 'X',
	TILE_CHEST = 'Q',
	TILE_KEY = 'K',
	TILE_COIN = 'C',
	TILE_TREE = 'T'
}	t_tile_type;

#endif
