/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_toupper.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:11:44 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/09 18:20:51 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Converts a lower-case letter to the corresponding upper-case letter.
** @param  c: The character to convert, passed as an int.
** @return If the argument is a lower-case letter, the toupper function
**         returns the corresponding upper-case letter if there is one;
**         otherwise, the argument is returned unchanged.
*/
int	ft_toupper(int c)
{
	if (c >= 'a' && c <= 'z')
		return (c - ('a' - 'A'));
	return (c);
}
