/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_strncmp.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:18:04 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 16:47:27 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Lexicographically compares not more than n characters of the
**         null-terminated strings s1 and s2.
** @param  s1: The first string to be compared.
** @param  s2: The second string to be compared.
** @param  n: The maximum number of characters to compare.
** @return An integer greater than, equal to, or less than 0, according
**         as the string s1 is greater than, equal to, or less than s2.
*/
int	ft_strncmp(const char *s1, const char *s2, size_t n)
{
	if (n == 0)
		return (0);
	while (*s1 != '\0' && *s2 != '\0' && *s1 == *s2 && n > 1)
	{
		s1++;
		s2++;
		n--;
	}
	return ((unsigned char)*s1 - (unsigned char)*s2);
}
