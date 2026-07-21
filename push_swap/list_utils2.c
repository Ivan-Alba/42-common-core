/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   list_utils2.c                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/14 15:57:01 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/22 18:54:31 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "push_swap.h"

/*
** @brief  Counts the total number of elements in a linked list.
** @param  lst: Pointer to the head of the list.
** @return Total node count, or 0 if the list is empty.
*/
int	ft_lstsize(t_list *lst)
{
	int	count;

	count = 0;
	if (!lst)
		return (0);
	while (lst)
	{
		lst = lst->next;
		count++;
	}
	return (count);
}

/*
** @brief  Adds a new node to the end of a linked list.
** @param  lst: Double pointer to the head of the list.
** @param  new: Pointer to the new node to be appended.
*/
void	ft_lstadd_back(t_list **lst, t_list *new)
{
	t_list	*last;

	if (!lst)
		lst = &new;
	else
	{
		if (!*lst)
			*lst = new;
		else
		{
			last = ft_lstlast(*lst);
			last->next = new;
		}
	}
}

/*
** @brief  Adds a new node to the beginning of a linked list.
** @param  lst: Double pointer to the head of the list.
** @param  new: Pointer to the new node to be prepended.
*/
void	ft_lstadd_front(t_list **lst, t_list *new)
{
	if (*lst)
		new->next = *lst;
	else
		new->next = NULL;
	*lst = new;
}
