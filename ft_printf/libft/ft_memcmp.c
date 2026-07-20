/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_memcmp.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/10 15:25:49 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 19:24:17 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Compares the first n bytes of the memory areas s1 and s2.
** @param  s1: Pointer to the first memory area.
** @param  s2: Pointer to the second memory area.
** @param  n: Number of bytes to compare.
** @return An integer less than, equal to, or greater than zero if the first
**         n bytes of s1 is found, respectively, to be less than, to match,
**         or be greater than the first n bytes of s2.
*/
int	ft_memcmp(const void *s1, const void *s2, size_t n)
{
	unsigned char	*tmp_s1;
	unsigned char	*tmp_s2;

	tmp_s1 = (unsigned char *)s1;
	tmp_s2 = (unsigned char *)s2;
	while (n > 0)
	{
		if (*tmp_s1 != *tmp_s2)
			return (*tmp_s1 - *tmp_s2);
		n--;
		tmp_s1++;
		tmp_s2++;
	}
	return (0);
}
