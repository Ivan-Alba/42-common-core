/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   texture_list.c                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/03 00:13:32 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:23:44 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"

t_texture	*get_texture(char id_txt, t_data *data)
{
	t_texture	*current;

	current = data->map_data->txt_list;
	while (current)
	{
		if (current->id_txt == id_txt)
			return (current);
		current = current->next;
	}
	return (NULL);
}

void	clear_txt_list(t_texture **txt_list, t_mlx *mlx_data)
{
	if (!(*txt_list) || !txt_list)
		return ;
	clear_txt_list(&(*txt_list)->next, mlx_data);
	if ((*txt_list)->path)
		free((*txt_list)->path);
	if ((*txt_list)->img)
		mlx_destroy_image(mlx_data->mlx_ptr, (*txt_list)->img);
	free(*txt_list);
	*txt_list = NULL;
}

static t_texture	*last_node(t_texture *txt_list)
{
	if (!txt_list)
		return (NULL);
	while (txt_list->next)
	{
		txt_list = txt_list->next;
	}
	return (txt_list);
}

static void	add_node_back(t_texture **txt_list, t_texture *new)
{
	t_texture	*last;

	if (!txt_list)
		txt_list = &new;
	else
	{
		if (!*txt_list)
			*txt_list = new;
		else
		{
			last = last_node(*txt_list);
			last->next = new;
		}
	}
}

void	add_texture_node(char id_texture, char *path, t_data *data)
{
	t_texture	*new;

	new = (t_texture *)malloc(sizeof(t_texture));
	if (!new)
		malloc_protection(new, data);
	new->img = NULL;
	new->id_txt = id_texture;
	new->path = path;
	new->addr = NULL;
	new->next = NULL;
	add_node_back(&data->map_data->txt_list, new);
}
