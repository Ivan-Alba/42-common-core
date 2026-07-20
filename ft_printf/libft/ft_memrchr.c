/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_memrchr.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 19:17:46 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/12 21:28:44 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Scans the memory area pointed to by s backwards for the first
**         instance of c, looking only at the first n bytes.
** @param  s: Pointer to the memory area to be scanned.
** @param  c: Character to be located, passed as an int.
** @param  n: Number of bytes to analyze.
** @return A pointer to the matching byte or NULL if the character does
**         not occur in the given memory area.
*/
void	*ft_memrchr(const void *s, int c, size_t n)
{
	unsigned char	*tmp;

	tmp = (unsigned char *)s;
	while (n > 0)
	{
		n--;
		if (tmp[n] == (unsigned char)c)
			return ((void *)&tmp[n]);
	}
	return (NULL);
}
