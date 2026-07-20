/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_itoa.c                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/11 21:05:25 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/15 18:55:41 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Counts the number of characters needed to represent the integer n,
**         including the minus sign if negative.
*/
static int	count_char(int n)
{
	int	dig;

	if (n == -2147483648)
		return (11);
	if (n == 0)
		return (1);
	dig = 0;
	if (n < 0)
	{
		dig++;
		n *= -1;
	}
	while (n > 0)
	{
		n /= 10;
		dig++;
	}
	return (dig);
}

/*
** @brief  Fills the allocated string with the characters representing n.
**         Iterates backwards from the end of the string to avoid powers of 10.
*/
static char	*put_string(int n, char *res, int dig)
{
	int	is_negative;

	res[dig] = '\0';
	if (n == 0)
	{
		res[0] = '0';
		return (res);
	}
	is_negative = 0;
	if (n < 0)
	{
		is_negative = 1;
		res[0] = '-';
	}
	while (dig > is_negative)
	{
		dig--;
		if (is_negative)
			res[dig] = -1 * (n % 10) + '0';
		else
			res[dig] = (n % 10) + '0';
		n /= 10;
	}
	return (res);
}

/*
** @brief  Allocates and returns a string representing the integer received
**         as an argument. Negative numbers must be handled.
** @param  n: The integer to convert.
** @return The string representing the integer, or NULL if allocation fails.
*/
char	*ft_itoa(int n)
{
	char	*res;
	int		dig;

	if (n == -2147483648)
		return (ft_strdup("-2147483648"));
	dig = count_char(n);
	res = (char *)malloc((dig + 1) * sizeof(char));
	if (!res)
		return (NULL);
	return (put_string(n, res, dig));
}
