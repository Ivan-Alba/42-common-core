/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_strlcat.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/10 16:41:22 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/13 14:21:37 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Appends string src to the end of dst. It will append at most
**         size - strlen(dst) - 1 characters. It will then NUL-terminate,
**         unless size is 0 or the original dst string was longer than size.
** @param  dst: The destination string.
** @param  src: The source string.
** @param  size: The total size of the destination buffer.
** @return The total length of the string they tried to create.
*/
size_t	ft_strlcat(char *dst, const char *src, size_t size)
{
	size_t	dst_len;
	size_t	src_len;
	size_t	i;
	size_t	j;

	src_len = ft_strlen(src);
	if (size == 0)
		return (src_len);
	dst_len = ft_strlen(dst);
	if (size <= dst_len)
		return (size + src_len);
	i = dst_len;
	j = 0;
	while (src[j] != '\0' && (i + 1) < size)
	{
		dst[i] = src[j];
		i++;
		j++;
	}
	dst[i] = '\0';
	return (dst_len + src_len);
}
