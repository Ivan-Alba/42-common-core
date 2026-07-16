/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   error.h                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/04 20:58:02 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 20:58:29 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#ifndef ERROR_H
# define ERROR_H

# define ERR_ARGS "Error\nInvalid number of arguments\n"
# define ERR_WINDOW_SIZE "Error\nHeight and width must be at least 100 pixels\n"
# define ERR_HEIGHT "Error\nWidth must be greater than height\n"
# define ERR_MAX_RES "Error\nMax. resolution exceeded\n"
# define ERR_EXT "Error\nInvalid file extension\n"
# define ERR_MALLOC "Error\nMemory allocation failed\n"
# define ERR_CPY "Error\nMemory error when duplicating map\n"
# define ERR_EMPTY "Error\nFile is Empty\n"
# define ERR_MAP "Error\nInvalid map\n"
# define ERR_TXT "Error\nInvalid textures in configuration map\n"
# define ERR_PATH "Error\nCan't open texture path\n"
# define ERR_NEXT "Error\nNext map file is not a .cub file\n"
# define ERR_DUPL " texture is duplicated on .cub file\n"
# define ERR_OPEN_NEXT "Error\nCan't open next_map file\n"
# define ERR_COLOR "Error\n.Invalid color format\n"
# define ERR_RGB "Error\n. Invalid RGB configuration\n"
# define ERR_FILE "Error\nInvalid file\n"
# define ERR_OPEN "Error\nCould not open file\n"
# define ERR_CONF "Error\nRequired sections missing from .cub file\n"
# define ERR_INV_CHAR "Error\nInvalid character in map\n"
# define ERR_PLAYER "Error\nMap must have only one player\n"
# define ERR_EXIT "Error\nMap must have one exit as a maximum\n"
# define ERR_NO_EXIT "Error\nPlayer can't access to the exit\n"
# define ERR_DOOR "Error\nInvalid door at map\n"
# define ERR_NO_COLLECT "Error\nPlayer can't access to all the collectibles\n"
# define ERR_KEY "Error\nPlayer can't access to the key\n"
# define ERR_CHEST "Error\nPlayer can't access to all the chests\n"
# define ERR_COIN "Error\nPlayer can't access to all the coins\n"
# define ERR_KET_QT "Error\nMap must have one key exit as a maximun\n"
# define ERR_BORDER "Error\nThe map must be closed/surrounded by walls\n"
# define ERR_SOLUT "Error\nInvalid Map, player can't visit all the spaces\n"
# define ERR_START "Error\nCan't open start screen files\n"
# define NO_PLAY "Don't you want to play? Maybe later, see you :)!!\n"
# define ERR_MLX_INIT "Error\nmlx : initialization failed\n"
# define ERR_MLX_WIN "Error\nmlx : window creation failed\n"
# define ERR_MLX_IMG "Error\nmlx : image creation failed\n"
# define ERR_LOAD_TXT "Error\nTextures load failed\n"
# define ERR_LOAD_IMG "Error\nError loading images\n"
# define ERR_MAP_CHAR "Error\nInvalid map character\n"
# define ERR_MAP_BORDER "Error\nMap is not closed\n"
# define ERR_MAP_PLAYER "Error\nPlayer is not in the map\n"
# define ERR_MAP_EMPTY "Error\nMap is empty\n"
# define ERR_BASS "Error\nBass Library failed\n"
# define ERR_BASS_FILE "Error\nBass Create File failed\n"

#endif
