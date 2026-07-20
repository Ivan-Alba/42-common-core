/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_int_flags_bonus.c                               :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/30 16:16:43 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/31 19:53:15 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "ft_printf.h"

/*
** @brief  Handles formatting for integers with left-justify flag ('-').
** @param  num: The integer value to print cast to long.
** @param  flags: A pointer to the formatting status structure.
** @param  sign: Sign flag indicator (-1 if negative, 0 otherwise).
** @return The total length printed, or -1 on failure.
*/
int	print_int_justify(long num, t_flags *flags, int sign)
{
	int		len;
	int		check;
	char	*s;

	check = print_int_sign(sign, flags);
	if (check == -1)
		return (-1);
	s = transform_number(num);
	if (!s)
		return (-1);
	len = check + ft_strlen(s);
	if (flags->truncate > (int)ft_strlen(s))
	{
		check = print_x_char(flags->truncate - (int)ft_strlen(s), '0');
		if (check == -1)
			return (free_and_out(s));
		len += check;
	}
	if (flags->width - len <= 0)
		return ((len - ft_strlen(s)) + ft_print_str(s, 1));
	else
		return (print_str_width(s, flags, len - ft_strlen(s)));
}

/*
** @brief  Handles formatting for integers with precision flag ('.').
** @param  num: The integer value to print cast to long.
** @param  flags: A pointer to the formatting status structure.
** @param  sign: Sign flag indicator (-1 if negative, 0 otherwise).
** @return The total length printed, or -1 on failure.
*/
int	print_int_truncate(long num, t_flags *flags, int sign)
{
	int		len;
	char	*s;

	len = 0;
	s = transform_number(num);
	if (!s)
		return (-1);
	len = ft_strlen(s);
	if (flags->space || flags->sign || sign == -1)
		len++;
	if (flags->truncate >= (int)ft_strlen(s))
		len += flags->truncate - (int)ft_strlen(s);
	while (len < flags->width)
	{
		if (print_x_char(1, ' ') == -1)
			return (free_and_out(s));
		len++;
	}
	if (print_int_sign(sign, flags) == -1
		|| print_x_char(flags->truncate - (int)ft_strlen(s), '0') == -1)
		return (free_and_out(s));
	if (ft_print_str(s, 1) == -1)
		return (-1);
	return (len);
}

/*
** @brief  Outputs the appropriate sign modifier ('+', '-', or ' ').
** @param  num: Sign indicator state value.
** @param  flags: A pointer to the formatting status structure.
** @return The number of bytes printed (1), or -1 on system failure.
*/
int	print_int_sign(int num, t_flags *flags)
{
	if (flags->sign && num >= 0)
	{
		if (write(1, "+", 1) == -1)
			return (-1);
	}
	else if (flags->sign || flags->space || num < 0)
	{
		if (flags->space && num >= 0)
		{
			if (write(1, " ", 1) == -1)
				return (-1);
		}
		else
		{
			if (write(1, "-", 1) == -1)
				return (-1);
		}
	}
	return (1);
}

/*
** @brief  Handles formatting for integers with zero-fill flag ('0').
** @param  num: The integer value to print cast to long.
** @param  flags: A pointer to the formatting status structure.
** @param  sign: Sign flag indicator (-1 if negative, 0 otherwise).
** @return The total length printed, or -1 on failure.
*/
int	print_int_zero(long num, t_flags *flags, int sign)
{
	char	*s;
	int		len;
	int		check;

	s = transform_number(num);
	if (!s)
		return (-1);
	len = ft_strlen(s);
	check = print_int_sign(sign, flags);
	if (check == -1)
		return (free_and_out(s));
	len += check;
	if (print_x_char(flags->width - len, '0') == -1)
		return (free_and_out(s));
	if (ft_print_str(s, 1) == -1)
		return (-1);
	if (flags->width - len > 0)
		return (flags->width);
	return (len);
}

/*
** @brief  Handles formatting for integers with standard width.
** @param  num: The integer value to print cast to long.
** @param  flags: A pointer to the formatting status structure.
** @param  sign: Sign flag indicator (-1 if negative, 0 otherwise).
** @return The total length printed, or -1 on failure.
*/
int	print_int_width(long num, t_flags *flags, int sign)
{
	char	*s;
	int		len;

	s = transform_number(num);
	if (!s)
		return (-1);
	len = ft_strlen(s);
	if (flags->space || flags->sign || sign == -1)
		len++;
	if (flags->width - len > 0)
	{
		if (print_x_char(flags->width - len, ' ') == -1)
			return (free_and_out(s));
	}
	if (print_int_sign(sign, flags) == -1)
		return (free_and_out(s));
	if (ft_print_str(s, 1) == -1)
		return (-1);
	if (flags->width - len > 0)
		return (flags->width);
	return (len);
}
