/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_strmapi.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/12 16:53:52 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/13 14:44:24 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Applies the function 'f' to each character of the string 's'
**         to create a new string resulting from successive applications of 'f'.
** @param  s: The string to iterate on.
** @param  f: The function to apply to each character.
** @return The string created from the successive applications of 'f',
**         or NULL if the allocation fails or if s or f are NULL.
*/
char	*ft_strmapi(char const *s, char (*f)(unsigned int, char))
{
	char			*res;
	unsigned int	i;
	size_t			len;

	if (!s || !f)
		return (NULL);
	len = ft_strlen(s);
	res = (char *)malloc((len + 1) * sizeof(char));
	if (!res)
		return (NULL);
	i = 0;
	while (s[i] != '\0')
	{
		res[i] = f(i, s[i]);
		i++;
	}
	res[i] = '\0';
	return (res);
}
