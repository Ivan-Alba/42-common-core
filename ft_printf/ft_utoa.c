/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_utoa.c                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/20 13:21:00 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/30 16:22:51 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "ft_printf.h"

/*
** @brief  Counts the number of digits in an unsigned integer.
** @param  n: The unsigned integer to process.
** @return The total number of digits.
*/
static int	count_char(unsigned int n)
{
	int	dig;

	dig = 0;
	while (n > 0)
	{
		n /= 10;
		dig++;
	}
	return (dig);
}

/*
** @brief  Calculates a power of ten based on a digit count.
** @param  dig: The exponent determining the power level.
** @return The calculated power of ten.
*/
static int	pow_ten(int dig)
{
	int	res;

	res = 1;
	while (dig > 1)
	{
		res *= 10;
		dig--;
	}
	return (res);
}

/*
** @brief  Fills an allocated buffer with characters from an unsigned int.
** @param  n: The unsigned integer value to extract digits from.
** @param  res: The destination character buffer string.
** @param  dig: The length size of the numeric representation.
** @return A pointer to the filled string buffer.
*/
static char	*put_string(unsigned int n, char *res, int dig)
{
	int	i;

	i = 0;
	while (dig > 0)
	{
		res[i++] = (n / pow_ten(dig)) + '0';
		n -= (n / pow_ten(dig)) * pow_ten(dig);
		dig--;
	}
	res[i] = '\0';
	return (res);
}

/*
** @brief  Converts an unsigned integer into a allocated string.
** @param  n: The unsigned integer value to convert.
** @return A pointer to the newly allocated string, or NULL if it fails.
*/
char	*ft_utoa(unsigned int n)
{
	char	*res;
	int		dig;

	if (n == 0)
	{
		res = (char *) malloc(2 * sizeof(char));
		if (!res)
			return (NULL);
		res[0] = '0';
		res[1] = '\0';
		return (res);
	}
	else
	{
		dig = count_char(n);
		res = (char *) malloc((dig + 1) * sizeof(char));
		if (!res)
			return (NULL);
		return (put_string(n, res, dig));
	}
}
