/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_isprint.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:10:55 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 14:52:28 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Checks for any printable character including space.
** @param  c: The character to be checked, passed as an int.
** @return 1 if the character is printable, 0 otherwise.
*/
int	ft_isprint(int c)
{
	return (c >= 32 && c <= 126);
}
