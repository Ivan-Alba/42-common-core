/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_lstclear.c                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/17 16:09:44 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/17 16:56:23 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Deletes and frees the given node and every successor of that node,
**         using the function 'del' and free(3). Finally, the pointer to
**         the list is set to NULL.
** @param  lst: A pointer to the first link of a list.
** @param  del: A pointer to the function used to delete the content.
** @return None.
*/
void	ft_lstclear(t_list **lst, void (*del)(void *))
{
	t_list	*current;
	t_list	*next_node;

	if (!lst || !del || !*lst)
		return ;
	current = *lst;
	while (current)
	{
		next_node = current->next;
		del(current->content);
		free(current);
		current = next_node;
	}
	*lst = NULL;
}
