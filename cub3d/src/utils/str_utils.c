/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   str_utils.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/20 16:29:12 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/22 07:09:07 by mirifern         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"

//Add to an existing char** a new value, freeing the memory and re-allocating it
char	**add_to_array(char	***current, char *new_value)
{
	char	**new;
	int		i;

	if (!(*current) || !(*current)[0])
		new = malloc(sizeof(char *) * 2);
	else
	{
		i = 0;
		while (*current && (*current)[i])
			i++;
		new = malloc(sizeof(char *) * (i + 2));
	}
	if (!new)
		return (NULL);
	i = 0;
	while (*current && (*current)[i])
	{
		new[i] = ft_strdup((*current)[i]);
		i++;
	}
	new[i++] = ft_strdup(new_value);
	new[i] = NULL;
	if (*current)
		free_str_array(current);
	return (new);
}
