/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_memset.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:11:31 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/12 21:29:35 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Fills the first len bytes of the memory area pointed to by ptr
**         with the constant byte c.
** @param  ptr: Pointer to the memory area to fill.
** @param  c: Value to be set, passed as an int (converted to an unsigned char).
** @param  len: Number of bytes to be set to the value.
** @return A pointer to the memory area ptr.
*/
void	*ft_memset(void *ptr, int c, size_t len)
{
	unsigned char	*tmp;

	tmp = (unsigned char *)ptr;
	while (len > 0)
	{
		*tmp = (unsigned char)c;
		tmp++;
		len--;
	}
	return (ptr);
}
