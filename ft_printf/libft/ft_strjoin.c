/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_strjoin.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/10 18:25:06 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/13 13:15:22 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Allocates and returns a new string, which is the result
**         of the concatenation of 's1' and 's2'.
** @param  s1: The prefix string.
** @param  s2: The suffix string.
** @return The new string, or NULL if the allocation fails.
*/
char	*ft_strjoin(char const *s1, char const *s2)
{
	char	*joined;
	size_t	len1;
	size_t	len2;

	if (!s1 || !s2)
		return (NULL);
	len1 = ft_strlen(s1);
	len2 = ft_strlen(s2);
	joined = (char *)malloc((len1 + len2 + 1) * sizeof(char));
	if (!joined)
		return (NULL);
	ft_memcpy(joined, s1, len1);
	ft_memcpy(joined + len1, s2, len2 + 1);
	return (joined);
}
