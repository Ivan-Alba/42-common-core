/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   door_list.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/03 00:13:32 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:47:36 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"

t_door	*get_door(int grid[2], t_data *data)
{
	t_door	*current;

	current = data->map_data->door_list;
	while (current)
	{
		if (current->grid[X] == grid[X] && current->grid[Y] == grid[Y])
			return (current);
		current = current->next;
	}
	return (NULL);
}

void	clear_door_list(t_door **door_list)
{
	if (!(*door_list) || !door_list)
		return ;
	clear_door_list(&(*door_list)->next);
	free(*door_list);
	*door_list = NULL;
}

static t_door	*last_node(t_door *door_list)
{
	if (!door_list)
		return (NULL);
	while (door_list->next)
	{
		door_list = door_list->next;
	}
	return (door_list);
}

static void	add_node_back(t_door **door_list, t_door *new)
{
	t_door	*last;

	if (!door_list)
		door_list = &new;
	else
	{
		if (!*door_list)
			*door_list = new;
		else
		{
			last = last_node(*door_list);
			last->next = new;
		}
	}
}

void	add_door_node(int grid[2], int orient, int locked, t_data *data)
{
	t_door	*new;

	new = (t_door *)malloc(sizeof(t_door));
	if (!new)
		malloc_protection(new, data);
	new->grid[X] = grid[X];
	new->grid[Y] = grid[Y];
	new->state = CLOSED;
	new->orient = orient;
	new->is_locked = locked;
	new->next = NULL;
	add_node_back(&data->map_data->door_list, new);
}
