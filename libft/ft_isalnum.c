/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_isalnum.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/09 18:09:24 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/12 20:34:15 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Checks for an alphanumeric character; it is equivalent to
**         (ft_isalpha(c) || ft_isdigit(c)).
** @param  c: The character to be checked, passed as an int.
** @return 1 if the character is alphanumeric, 0 otherwise.
*/
int	ft_isalnum(int c)
{
	return (ft_isalpha(c) || ft_isdigit(c));
}
