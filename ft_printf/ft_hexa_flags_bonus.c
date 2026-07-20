/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_hexa_flags_bonus.c                              :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/30 16:17:16 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/02 17:37:36 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "ft_printf.h"

/*
** @brief  Handles formatting for %x and %X with precision flag.
** @param  s: The hexadecimal string to format and print.
** @param  flags: A pointer to the formatting status structure.
** @param  caps: Uppercase flag (1 for %X, 0 for %x).
** @return The total length printed, or -1 on error.
*/
int	print_hexa_truncate(char *s, t_flags *flags, int caps)
{
	int	len;

	len = ft_strlen(s);
	if (flags->format)
		len += 2;
	if (flags->truncate - (int)ft_strlen(s) > 0)
		len += flags->truncate - (int)ft_strlen(s);
	while (len < flags->width)
	{
		if (write(1, " ", 1) == -1)
			return (free_and_out(s));
		len++;
	}
	if (flags->format)
	{
		if (print_hexa_format(caps) == -1)
			return (free_and_out(s));
	}
	if (print_x_char(flags->truncate - (int)ft_strlen(s), '0') == -1)
		return (free_and_out(s));
	if (ft_print_str(s, 1) == -1)
		return (-1);
	return (len);
}

/*
** @brief  Outputs the alternate form prefix ("0x" or "0X").
** @param  caps: Uppercase indicator (1 for "0X", 0 for "0x").
** @return The number of bytes written (2), or -1 on error.
*/
int	print_hexa_format(int caps)
{
	if (caps)
	{
		if (write(1, "0X", 2) == -1)
			return (-1);
	}
	else
	{
		if (write(1, "0x", 2) == -1)
			return (-1);
	}
	return (2);
}

/*
** @brief  Handles formatting for %x and %X with zero-fill flag ('0').
** @param  s: The hexadecimal string to format and print.
** @param  flags: A pointer to the formatting status structure.
** @param  caps: Uppercase flag (1 for %X, 0 for %x).
** @return The total length printed, or -1 on error.
*/
int	print_hexa_zero(char *s, t_flags *flags, int caps)
{
	int	len;

	len = ft_strlen(s);
	if (flags->format)
	{
		if (print_hexa_format(caps) == -1)
			return (free_and_out(s));
		len += 2;
	}
	if (print_x_char(flags->width - len, '0') == -1)
		return (free_and_out(s));
	if (ft_print_str(s, 1) == -1)
		return (-1);
	if (flags->width - len > 0)
		return (flags->width);
	return (len);
}

/*
** @brief  Handles formatting for %x and %X with left-justify flag ('-').
** @param  s: The hexadecimal string to format and print.
** @param  flags: A pointer to the formatting status structure.
** @param  caps: Uppercase flag (1 for %X, 0 for %x).
** @return The total length printed, or -1 on error.
*/
int	print_hexa_justify(char *s, t_flags *flags, int caps)
{
	int	len;

	len = ft_strlen(s);
	if (flags->format)
	{
		if (print_hexa_format(caps) == -1)
			return (free_and_out(s));
		len += 2;
	}
	if (flags->truncate > (int)ft_strlen(s))
	{
		if (print_x_char(flags->truncate - ft_strlen(s), '0') == -1)
			return (free_and_out(s));
		len += (flags->truncate - (int)ft_strlen(s));
	}
	if (ft_print_str(s, 1) == -1)
		return (-1);
	if (flags->width - len > 0)
	{
		if (print_x_char(flags->width - len, ' ') == -1)
			return (-1);
		len += (flags->width - len);
	}
	return (len);
}

/*
** @brief  Handles formatting for %x and %X with standard width.
** @param  s: The hexadecimal string to format and print.
** @param  flags: A pointer to the formatting status structure.
** @param  caps: Uppercase flag (1 for %X, 0 for %x).
** @return The total length printed, or -1 on error.
*/
int	print_hexa_width(char *s, t_flags *flags, int caps)
{
	int	len;

	len = ft_strlen(s);
	if (flags->format)
		len += 2;
	if (print_x_char(flags->width - len, ' ') == -1)
		return (free_and_out(s));
	if (flags->format)
	{
		if (print_hexa_format(caps) == -1)
			return (free_and_out(s));
	}
	if (ft_print_str(s, 1) == -1)
		return (-1);
	if (flags->width - len > 0)
		return (flags->width);
	return (len);
}
