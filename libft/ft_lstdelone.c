/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_lstdelone.c                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/17 18:47:13 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/17 18:49:38 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Takes as a parameter a node and frees the memory of the node's
**         content using the function 'del' given as a parameter and free
**         the node. The memory of 'next' must not be freed.
** @param  lst: The node to free.
** @param  del: The pointer to the function used to delete the content.
** @return None.
*/
void	ft_lstdelone(t_list *lst, void (*del)(void *))
{
	if (!lst || !del)
		return ;
	del(lst->content);
	free(lst);
}
