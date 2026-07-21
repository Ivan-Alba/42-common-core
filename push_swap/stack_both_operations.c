/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   stack_both_operations.c                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/23 15:38:54 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/23 16:04:16 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "push_swap.h"

/*
** @brief  Rotates the top elements of both stack A and stack B upwards (rr).
** @param  stack_a: Double pointer to stack A.
** @param  stack_b: Double pointer to stack B.
** @param  order: String representing the operation command to write.
*/
void	rotate_both_stacks(t_list **stack_a, t_list **stack_b, char *order)
{
	rotate_stack(stack_a, NULL);
	rotate_stack(stack_b, NULL);
	protected_write(order, 3);
}

/*
** @brief  Rotates the bottom elements of both stack A and stack B
**         downwards (rrr).
** @param  stack_a: Double pointer to stack A.
** @param  stack_b: Double pointer to stack B.
** @param  order: String representing the operation command to write.
*/
void	rev_rotate_both_stacks(t_list **stack_a, t_list **stack_b, char *order)
{
	reverse_rotate_stack(stack_a, NULL);
	reverse_rotate_stack(stack_b, NULL);
	protected_write(order, 4);
}

/*
** @brief  Swaps the first two elements at the top of both stack A and
**         stack B (ss).
** @param  stack_a: Double pointer to stack A.
** @param  stack_b: Double pointer to stack B.
** @param  order: String representing the operation command to write.
*/
void	swap_both_stacks(t_list **stack_a, t_list **stack_b, char *order)
{
	swap_stack(stack_a, NULL);
	swap_stack(stack_b, NULL);
	protected_write(order, 3);
}
