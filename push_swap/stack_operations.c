/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   stack_operations.c                                 :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/14 15:43:49 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/23 16:05:18 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "push_swap.h"

/*
** @brief  Swaps the first two elements at the top of a stack (sa/sb).
** @param  stack: Double pointer to the target stack.
** @param  order: String representing the operation command to write.
*/
void	swap_stack(t_list **stack, char *order)
{
	t_list	*tmp;

	if (ft_lstsize(*stack) < 2)
		return ;
	tmp = ((*stack)->next)->next;
	((*stack)->next)->next = *stack;
	*stack = (*stack)->next;
	((*stack)->next)->next = tmp;
	protected_write(order, 3);
}

/*
** @brief  Pushes the top element from source stack to destination
**         stack (pa/pb).
** @param  stack_src: Double pointer to the source stack.
** @param  stack_dst: Double pointer to the destination stack.
** @param  order: String representing the operation command to write.
*/
void	push_stack(t_list **stack_src, t_list **stack_dst, char *order)
{
	t_list	*tmp;

	if (!stack_src || !(*stack_src))
		return ;
	tmp = (*stack_src)->next;
	(*stack_src)->next = NULL;
	ft_lstadd_front(stack_dst, *stack_src);
	*stack_src = tmp;
	protected_write(order, 3);
}

/*
** @brief  Rotates all elements of a stack upwards by one position (ra/rb).
** @param  stack: Double pointer to the target stack.
** @param  order: String representing the operation command to write.
*/
void	rotate_stack(t_list **stack, char *order)
{
	t_list	*tmp;

	if (ft_lstsize(*stack) <= 1)
		return ;
	tmp = *stack;
	*stack = (*stack)->next;
	tmp->next = NULL;
	ft_lstadd_back(stack, tmp);
	protected_write(order, 3);
}

/*
** @brief  Rotates all elements of a stack downwards by one position (rra/rrb).
** @param  stack: Double pointer to the target stack.
** @param  order: String representing the operation command to write.
*/
void	reverse_rotate_stack(t_list **stack, char *order)
{
	t_list	*tmp;
	int		size;

	size = ft_lstsize(*stack);
	if (size <= 1)
		return ;
	tmp = ft_lstlast(*stack);
	tmp->next = NULL;
	ft_lstadd_front(stack, tmp);
	*stack = tmp;
	while (size-- > 1)
		*stack = (*stack)->next;
	(*stack)->next = NULL;
	*stack = tmp;
	protected_write(order, 4);
}
