/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_memchr.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:49:01 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 17:15:48 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Scans the initial n bytes of the memory area pointed to by s
**         for the first instance of c. Both c and the bytes of the
**         memory area pointed to by s are interpreted as unsigned char.
** @param  s: Pointer to the memory area to be scanned.
** @param  c: Character to be located, passed as an int.
** @param  n: Number of bytes to analyze.
** @return A pointer to the matching byte or NULL if the character does
**         not occur in the given memory area.
*/
void	*ft_memchr(const void *s, int c, size_t n)
{
	unsigned char	*tmp;
	unsigned char	ch;

	tmp = (unsigned char *)s;
	ch = (unsigned char)c;
	while (n > 0)
	{
		if (*tmp == ch)
			return ((void *)tmp);
		tmp++;
		n--;
	}
	return (NULL);
}
