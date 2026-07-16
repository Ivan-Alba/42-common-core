/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   audio_list.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/03 00:13:32 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 22:23:44 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"

t_audio	*get_audio(char id_audio, t_data *data)
{
	t_audio	*current;

	current = data->audio_list;
	while (current)
	{
		if (current->audio_id == id_audio)
			return (current);
		current = current->next;
	}
	return (NULL);
}

void	clear_audio_list(t_audio **audio_list)
{
	if (!(*audio_list) || !audio_list)
		return ;
	clear_audio_list(&(*audio_list)->next);
	free(*audio_list);
	*audio_list = NULL;
}

static t_audio	*last_node(t_audio *audio_list)
{
	if (!audio_list)
		return (NULL);
	while (audio_list->next)
	{
		audio_list = audio_list->next;
	}
	return (audio_list);
}

static void	add_node_back(t_audio **audio_list, t_audio *new)
{
	t_audio	*last;

	if (!audio_list)
		audio_list = &new;
	else
	{
		if (!*audio_list)
			*audio_list = new;
		else
		{
			last = last_node(*audio_list);
			last->next = new;
		}
	}
}

void	add_audio_node(char id_audio, uint32_t bass_id, t_data *data)
{
	t_audio	*new;

	new = (t_audio *)malloc(sizeof(t_audio));
	if (!new)
		malloc_protection(new, data);
	new->audio_id = id_audio;
	new->bass_id = bass_id;
	new->next = NULL;
	add_node_back(&data->audio_list, new);
}
