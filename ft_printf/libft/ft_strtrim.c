/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_strtrim2.c                                      :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/13 16:43:53 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/13 17:10:12 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Allocates and returns a copy of 's1' with the characters
**         specified in 'set' removed from the beginning and the end
**         of the string.
** @param  s1: The string to be trimmed.
** @param  set: The reference set of characters to trim.
** @return The trimmed string, or NULL if the allocation fails.
*/
char	*ft_strtrim(char const *s1, char const *set)
{
	size_t	start;
	size_t	end;
	char	*trimmed;

	if (!s1 || !set)
		return (NULL);
	start = 0;
	end = ft_strlen(s1);
	while (s1[start] != '\0' && ft_strchr(set, s1[start]))
		start++;
	while (end > start && ft_strchr(set, s1[end - 1]))
		end--;
	trimmed = (char *)malloc((end - start + 1) * sizeof(char));
	if (!trimmed)
		return (NULL);
	ft_strlcpy(trimmed, &s1[start], end - start + 1);
	return (trimmed);
}
