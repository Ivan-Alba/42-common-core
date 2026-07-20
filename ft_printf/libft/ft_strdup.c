/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_strdup.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/10 18:05:39 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 19:29:07 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Allocates sufficient memory for a copy of the string s,
**         does the copy, and returns a pointer to it.
** @param  s: The string to duplicate.
** @return A pointer to the duplicated string, or NULL if allocation fails.
*/
char	*ft_strdup(const char *s)
{
	char	*cpy;
	size_t	len;

	len = ft_strlen(s);
	cpy = (char *)malloc((len + 1) * sizeof(char));
	if (!cpy)
		return (NULL);
	ft_memcpy(cpy, s, len + 1);
	return (cpy);
}
