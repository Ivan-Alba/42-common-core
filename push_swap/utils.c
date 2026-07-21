/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   utils.c                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/23 15:44:02 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/23 16:05:49 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "push_swap.h"

/*
** @brief  Frees allocated memory pointer, prints error message, and exits
**         program.
** @param  ptr: Pointer to memory address to be freed.
*/
void	free_and_exit(void *ptr)
{
	if (ptr)
	{
		free(ptr);
		ptr = NULL;
	}
	write(2, ERROR_MSG, sizeof(ERROR_MSG));
	exit(0);
}

/*
** @brief  Allocates memory for an array and initializes all bytes to zero.
** @param  nelem: Number of elements to allocate.
** @param  elsize: Size in bytes of each element.
** @return Pointer to allocated memory, or NULL if allocation fails.
*/
void	*ft_calloc(size_t nelem, size_t elsize)
{
	void	*res;

	res = malloc(nelem * elsize);
	if (res == NULL)
		return (res);
	else
		ft_bzero(res, nelem * elsize);
	return (res);
}

/*
** @brief  Erases data in the n bytes of memory starting at the given location.
** @param  s: Pointer to target memory block.
** @param  n: Number of bytes to set to zero.
*/
void	ft_bzero(void *s, size_t n)
{
	char	*tmp;

	tmp = s;
	while (n > 0)
	{
		*tmp = 0;
		tmp++;
		n--;
	}
}

/*
** @brief  Writes a string command to standard output; exits on write error.
** @param  order: Instruction string to be printed.
** @param  size: Length of string in bytes to write.
*/
void	protected_write(char *order, int size)
{
	if (order)
	{
		if (write(1, order, size) == -1)
			free_and_exit(order);
	}
}
