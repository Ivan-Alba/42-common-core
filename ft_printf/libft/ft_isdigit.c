/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_isdigit.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:09:44 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/09 18:12:57 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Checks for a digit (0 through 9).
** @param  c: The character to be checked, passed as an int.
** @return 1 if the character is a digit, 0 otherwise.
*/
int	ft_isdigit(int c)
{
	return (c >= 48 && c <= 57);
}
