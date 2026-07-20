/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_lstiter.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/17 16:57:53 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/17 17:02:53 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Iterates the list 'lst' and applies the function 'f' on the content
**         of each node.
** @param  lst: A pointer to the first link of a list.
** @param  f: A pointer to the function used to iterate on the list.
** @return None.
*/
void	ft_lstiter(t_list *lst, void (*f)(void *))
{
	t_list	*curr;

	if (!lst || !f)
		return ;
	curr = lst;
	while (curr)
	{
		f(curr->content);
		curr = curr->next;
	}
}
