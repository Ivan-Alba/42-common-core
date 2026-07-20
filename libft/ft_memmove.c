/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_memmove.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/10 15:45:03 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 19:20:35 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Copies n bytes from memory area src to memory area dest.
**         The memory areas may overlap safely.
** @param  dest: Pointer to the destination array.
** @param  src: Pointer to the source of data to be copied.
** @param  n: Number of bytes to copy.
** @return A pointer to the destination memory area (dest).
*/
void	*ft_memmove(void *dest, const void *src, size_t n)
{
	unsigned char	*tmp_src;
	unsigned char	*tmp_dest;

	if (!dest && !src)
		return (NULL);
	tmp_src = (unsigned char *)src;
	tmp_dest = (unsigned char *)dest;
	if (dest <= src)
	{
		while (n--)
			*tmp_dest++ = *tmp_src++;
	}
	else
	{
		while (n--)
			tmp_dest[n] = tmp_src[n];
	}
	return (dest);
}
