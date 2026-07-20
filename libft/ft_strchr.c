/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_strchr.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:09:58 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 19:12:21 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Locates the first occurrence of c (converted to a char) in the
**         string pointed to by s. The terminating null character is
**         considered to be part of the string.
** @param  s: The string to be scanned.
** @param  c: The character to be located, passed as an int.
** @return A pointer to the located character, or NULL if the character
**         does not occur in the string.
*/
char	*ft_strchr(const char *s, int c)
{
	while (*s != '\0')
	{
		if (*s == (char)c)
			return ((char *)s);
		s++;
	}
	if (*s == (char)c)
		return ((char *)s);
	return (NULL);
}
