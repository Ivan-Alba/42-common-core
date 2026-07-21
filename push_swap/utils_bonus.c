/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   utils_bonus.c                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/22 17:53:35 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/24 14:52:40 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "push_swap.h"

/*
** @brief  Compares two strings for exact matching content.
** @param  next: String containing the instruction read.
** @param  order: Expected instruction string to compare against.
** @return 1 if both strings match, 0 otherwise.
*/
int	ft_strcmp(char *next, char *order)
{
	int	i;

	i = 0;
	while (next[i] != '\0')
	{
		if (next[i] != order[i])
			return (0);
		i++;
	}
	return (1);
}

/*
** @brief  Verifies if stack A contains all elements in strictly
**         ascending order.
** @param  stack_a: Double pointer to stack A.
** @param  count: Expected total number of elements.
** @return 1 if stack A is fully sorted, 0 otherwise.
*/
int	is_sorted(t_list **stack_a, int count)
{
	t_list	*tmp;

	tmp = *stack_a;
	if (ft_lstsize(*stack_a) != count)
		return (0);
	while (*stack_a)
	{
		if (!(*stack_a)->next)
			break ;
		else
		{
			if ((*stack_a)->index > ((*stack_a)->next)->index)
			{
				*stack_a = tmp;
				return (0);
			}
		}
		*stack_a = (*stack_a)->next;
	}
	*stack_a = tmp;
	return (1);
}

/*
** @brief  Frees allocated memory for both stacks, outputs error message,
**         and exits.
** @param  stack_a: Double pointer to stack A.
** @param  stack_b: Double pointer to stack B.
*/
void	free_both_stacks(t_list **stack_a, t_list **stack_b)
{
	free_stack(stack_a);
	free_stack(stack_b);
	write(2, ERROR_MSG, sizeof(ERROR_MSG));
	exit(0);
}
