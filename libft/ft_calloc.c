/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_calloc.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/10 17:56:46 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 19:32:54 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Allocates memory for an array of nelem elements of elsize bytes each
**         and returns a pointer to the allocated memory. The memory is set
**         to zero. If nelem or elsize is 0, it returns a unique pointer value
**         that can be safely passed to free().
** @param  nelem: Number of elements to allocate.
** @param  elsize: Size of each element in bytes.
** @return A pointer to the allocated memory, or NULL if the allocation fails.
*/
void	*ft_calloc(size_t nelem, size_t elsize)
{
	void	*res;
	size_t	total_size;

	total_size = nelem * elsize;
	if (nelem != 0 && total_size / nelem != elsize)
		return (NULL);
	if (total_size == 0)
		total_size = 1;
	res = malloc(total_size);
	if (res == NULL)
		return (NULL);
	ft_bzero(res, total_size);
	return (res);
}
