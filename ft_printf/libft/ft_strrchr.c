/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_strrchr.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:10:30 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 19:02:45 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Locates the last occurrence of c (converted to a char) in the
**         string pointed to by s. The terminating null character is
**         considered to be part of the string.
** @param  s: The string to be scanned.
** @param  c: The character to be located, passed as an int.
** @return A pointer to the located character, or NULL if the character
**         does not occur in the string.
*/
char	*ft_strrchr(const char *s, int c)
{
	size_t	len;

	len = ft_strlen(s);
	while (1)
	{
		if (s[len] == (char)c)
			return ((char *)&s[len]);
		if (len == 0)
			break ;
		len--;
	}
	return (NULL);
}
