/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_lstmap.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/17 17:02:59 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/17 19:09:25 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Iterates the list 'lst' and applies the function 'f' on the content
**         of each node. Creates a new list resulting from the successive
**         applications of the function 'f'. The 'del' function is used to
**         destroy the content of a node if needed.
** @param  lst: A pointer to the first link of a list.
** @param  f: A pointer to the function used to iterate on the list.
** @param  del: A pointer to the function used to delete the content if needed.
** @return The new list, or NULL if the allocation fails.
*/
t_list	*ft_lstmap(t_list *lst, void *(*f)(void *), void (*del)(void *))
{
	t_list	*new_list;
	t_list	*new_node;
	void	*new_content;

	if (!lst || !f || !del)
		return (NULL);
	new_list = NULL;
	while (lst)
	{
		new_content = f(lst->content);
		new_node = ft_lstnew(new_content);
		if (!new_node)
		{
			del(new_content);
			ft_lstclear(&new_list, del);
			return (NULL);
		}
		ft_lstadd_back(&new_list, new_node);
		lst = lst->next;
	}
	return (new_list);
}
