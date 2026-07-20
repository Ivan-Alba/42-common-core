/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_strnstr.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/10 16:23:45 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 17:26:36 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Locates the first occurrence of the null-terminated string little
**         in the string big, where not more than len characters are searched.
** @param  big: The string to be searched.
** @param  little: The substring to locate.
** @param  len: The maximum number of characters to search.
** @return If little is an empty string, big is returned; if little occurs
**         nowhere in big, NULL is returned; otherwise a pointer to the
**         first character of the first occurrence of little is returned.
*/
char	*ft_strnstr(const char *big, const char *little, size_t len)
{
	size_t	i;
	size_t	j;

	if (*little == '\0')
		return ((char *)big);
	i = 0;
	while (big[i] != '\0' && i < len)
	{
		j = 0;
		while (big[i + j] == little[j] && (i + j) < len)
		{
			if (little[j + 1] == '\0')
				return ((char *)&big[i]);
			j++;
		}
		i++;
	}
	return (NULL);
}
