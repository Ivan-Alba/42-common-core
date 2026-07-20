/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_bzero.c                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:23:41 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/12 19:59:46 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Erases the data in the n bytes of the memory starting at the location
**         pointed to by s, by writing zeros (bytes containing '\0') to that
**         area.
** @param  s: Pointer to the memory block to be zeroed.
** @param  n: Number of bytes to set to zero.
** @return None.
*/
void	ft_bzero(void *s, size_t n)
{
	unsigned char	*tmp;

	tmp = (unsigned char *)s;
	while (n > 0)
	{
		*tmp = 0;
		tmp++;
		n--;
	}
}
