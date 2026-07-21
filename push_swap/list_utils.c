/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   list_utils.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/12 12:32:43 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/22 18:54:45 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "push_swap.h"

/*
** @brief  Retrieves the last node of a linked list.
** @param  lst: Pointer to the head of the list.
** @return Pointer to the last node, or NULL if the list is empty.
*/
t_list	*ft_lstlast(t_list *lst)
{
	if (!lst)
		return (NULL);
	while (lst->next)
	{
		lst = lst->next;
	}
	return (lst);
}

/*
** @brief  Allocates and initializes a new list node with a given value.
** @param  value: Integer value to store in the node.
** @return Pointer to the newly allocated node, or NULL on allocation failure.
*/
t_list	*ft_lstnew(int value)
{
	t_list	*new;

	new = (t_list *) malloc(sizeof(t_list));
	if (!new)
		return (NULL);
	new->value = value;
	new->next = NULL;
	return (new);
}

/*
** @brief  Deletes and frees all nodes in a linked list recursively.
** @param  lst: Double pointer to the head of the list to be cleared.
*/
void	ft_lstclear(t_list **lst)
{
	if (!(*lst) || !lst)
		return ;
	ft_lstclear(&(*lst)->next);
	free(*lst);
	*lst = NULL;
}
