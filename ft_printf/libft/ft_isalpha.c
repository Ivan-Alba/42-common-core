/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_isalpha.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:10:46 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/12 20:35:55 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Checks for an alphabetic character; it is equivalent to
**         (isupper(c) || islower(c)).
** @param  c: The character to be checked, passed as an int.
** @return 1 if the character is alphabetic, 0 otherwise.
*/
int	ft_isalpha(int c)
{
	return ((c >= 65 && c <= 90) || (c >= 97 && c <= 122));
}
