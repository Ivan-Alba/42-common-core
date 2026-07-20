/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_tolower.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:11:14 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/13 15:35:26 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Converts an upper-case letter to the corresponding lower-case letter.
** @param  c: The character to convert, passed as an int.
** @return If the argument is an upper-case letter, the tolower function
**         returns the corresponding lower-case letter if there is one;
**         otherwise, the argument is returned unchanged.
*/
int	ft_tolower(int c)
{
	if (c >= 'A' && c <= 'Z')
		return (c + ('a' - 'A'));
	return (c);
}
