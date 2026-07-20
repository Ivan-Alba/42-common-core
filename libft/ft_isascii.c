/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_isascii.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:11:22 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/12 20:39:08 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Checks whether c is a 7-bit unsigned char value that
**         fits into the ASCII character set.
** @param  c: The character to be checked, passed as an int.
** @return 1 if the character is inside the ASCII set, 0 otherwise.
*/
int	ft_isascii(int c)
{
	return (c >= 0 && c <= 127);
}
