/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_atoi.c                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/10 16:52:35 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/12 18:56:06 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Converts the initial portion of the string pointed to by str to int.
** @param  str: The string representation of the integer to convert.
** @return The converted integer value.
*/
int	ft_atoi(const char *str)
{
	int	res;
	int	sign;

	sign = 1;
	res = 0;
	while ((*str >= 9 && *str <= 13) || (*str == 32))
		str++;
	if (*str == '-' || *str == '+')
	{
		if (*str == '-')
			sign = -1;
		str++;
	}
	while (*str >= '0' && *str <= '9')
	{
		res = (res * 10) + (*str - '0');
		str++;
	}
	return (res * sign);
}
