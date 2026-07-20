/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_utils_bonus.c                                   :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/25 20:05:41 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/01 16:53:09 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "ft_printf.h"

/*
** @brief  Prints a character a specified number of times.
** @param  qty: The number of times to repeat the character.
** @param  c: The character value to be written.
** @return The total length printed, or -1 on write failure.
*/
int	print_x_char(int qty, unsigned int c)
{
	int		len;
	char	ch;

	len = 0;
	ch = (char)c;
	while (qty-- > 0)
	{
		if (write(1, &ch, 1) == -1)
			return (-1);
		len++;
	}
	return (len);
}

/*
** @brief  Handles width and alignment padding for string outputs.
** @param  s: The string buffer to be printed and freed.
** @param  flags: A pointer to the formatting status structure.
** @param  flags_len: Additional length contributed by formatting.
** @return The total length printed, or -1 on failure.
*/
int	print_str_width(char *s, t_flags *flags, int flags_len)
{
	int	len;

	len = ft_strlen(s);
	len += flags_len;
	if (flags->left_justify)
	{
		if (ft_print_str(s, 1) == -1)
			return (-1);
		if (print_x_char(flags->width - len, ' ') == -1)
			return (-1);
		return (len + (flags->width - len));
	}
	else
	{
		if (print_x_char(flags->width - len, ' ') == -1)
			return (free_and_out(s));
		if (ft_print_str(s, 1) == -1)
			return (-1);
		return (flags->width);
	}
}

/*
** @brief  Converts a long number into its appropriate string form.
** @param  num: The numerical value cast to long.
** @return A pointer to the allocated string, or NULL if it fails.
*/
char	*transform_number(long num)
{
	char	*s;

	if (num > 2147483647)
		s = ft_utoa(num);
	else
		s = ft_itoa(num);
	if (!s)
		return (NULL);
	return (s);
}

/*
** @brief  Handles formatting cases when value is 0 with precision 0.
** @param  flags: A pointer to the formatting status structure.
** @return The total length printed, or -1 on failure.
*/
int	print_num_zero(t_flags *flags)
{
	if (flags->left_justify && flags->sign && flags->width > 1)
	{
		if (print_int_sign(0, flags) == -1
			|| print_x_char(flags->width - 1, ' ') == -1)
			return (-1);
		return (flags->width);
	}
	if (flags->width + flags->sign + flags->space == 0)
		return (0);
	else if (flags->width <= 1 && flags->space)
	{
		if (write(1, " ", 1) == -1)
			return (-1);
		return (1);
	}
	else
	{
		if (print_x_char(flags->width - flags->sign - flags->space, ' ') == -1)
			return (-1);
		if (print_int_sign(0, flags) == -1)
			return (-1);
	}
	if (!flags->width)
		return (flags->sign);
	return (flags->width);
}

/*
** @brief  Outputs alternate hex prefix directly before the string.
** @param  s: The hexadecimal string buffer to print and free.
** @param  caps: Uppercase flag indicator (1 for %X, 0 for %x).
** @return The total length printed, or -1 on failure.
*/
int	print_hexa_format_str(char *s, int caps)
{
	int	len;

	len = ft_strlen(s);
	if (print_hexa_format(caps) == -1)
		return (free_and_out(s));
	if (ft_print_str(s, 1) == -1)
		return (-1);
	return (2 + len);
}
