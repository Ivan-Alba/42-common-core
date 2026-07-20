/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_memcpy.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/10 15:43:36 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 15:02:52 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Copies n bytes from memory area src to memory area dest.
**         The memory areas must not overlap.
** @param  dest: Pointer to the destination array where the content is to be
**         copied.
** @param  src: Pointer to the source of data to be copied.
** @param  n: Number of bytes to copy.
** @return A pointer to the destination memory area (dest).
*/
void	*ft_memcpy(void *dest, const void *src, size_t n)
{
	unsigned char	*tmp_dest;
	unsigned char	*tmp_src;

	if (!dest && !src)
		return (NULL);
	tmp_dest = (unsigned char *)dest;
	tmp_src = (unsigned char *)src;
	while (n > 0)
	{
		*tmp_dest = *tmp_src;
		tmp_dest++;
		tmp_src++;
		n--;
	}
	return (dest);
}
