/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_print_flags_bonus.c                             :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/25 20:08:31 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/01 16:25:39 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "ft_printf.h"

/*
** @brief  Validates flags for %c and outputs padding and character.
** @param  c: The unsigned character value to print.
** @param  flags: A pointer to the formatting status structure.
** @return The total length printed, or -1 on failure.
*/
int	print_char_flags(unsigned int c, t_flags *flags)
{
	int		len;
	char	ch;

	len = 0;
	ch = (char)c;
	if (flags->left_justify && flags->width > 1)
	{
		if (write(1, &ch, 1) == -1)
			return (-1);
		if (flags->width > 1)
			len = print_x_char(flags->width - 1, ' ');
		if (len == -1)
			return (-1);
		return (len + 1);
	}
	len = print_x_char(flags->width - 1, ' ');
	if (len == -1 || write(1, &ch, 1) == -1)
		return (-1);
	return (len + 1);
}

/*
** @brief  Evaluates format flags for %p and routes representation.
** @param  ptr: The raw memory pointer address to process.
** @param  flags: A pointer to the formatting status structure.
** @return The total length printed, or -1 on failure.
*/
int	print_ptr_flags(uintptr_t ptr, t_flags *flags)
{
	char	*print;

	print = ft_read_ptr(ptr, "0123456789abcdef");
	if (!print)
		return (-1);
	if (flags->width - (int)ft_strlen(print) > 0)
		return (print_str_width(print, flags, 0));
	else
		return (ft_print_str(print, 1));
}

/*
** @brief  Evaluates flags for %s, handles sizing and output.
** @param  s: The original string pointer to process.
** @param  flags: A pointer to the formatting status structure.
** @return The total length printed, or -1 on failure.
*/
int	print_str_flags(char *s, t_flags *flags)
{
	char	*str;

	if (!s)
		s = ft_read_string(s);
	else
		s = ft_strdup(s);
	if (!s)
		return (-1);
	if (flags->truncate > -1 && flags->truncate < (int)ft_strlen(s))
		str = ft_substr(s, 0, flags->truncate);
	else
		str = ft_strdup(s);
	free(s);
	if (!str)
		return (-1);
	if (flags->width - (int)ft_strlen(str) > 0)
		return (print_str_width(str, flags, 0));
	else
		return (ft_print_str(str, 1));
}

/*
** @brief  Checks formatting flags for integers and delegates logic.
** @param  num: The integer value cast to long.
** @param  flags: A pointer to the formatting status structure.
** @return The total length printed, or -1 on failure.
*/
int	print_int_flags(long num, t_flags *flags)
{
	int	sign;

	sign = 0;
	if (num < 0)
	{
		sign = -1;
		num *= -1;
	}
	else if (num == 0 && flags->truncate == 0)
		return (print_num_zero(flags));
	if (flags->left_justify)
		return (print_int_justify(num, flags, sign));
	else if (flags->truncate >= 0)
		return (print_int_truncate(num, flags, sign));
	else if (flags->fill_zero)
		return (print_int_zero(num, flags, sign));
	else
		return (print_int_width(num, flags, sign));
}

/*
** @brief  Checks formatting flags for hex and routes execution.
** @param  num: The unsigned long value to convert.
** @param  flags: A pointer to the formatting status structure.
** @param  caps: Uppercase flag (1 for %X, 0 for %x).
** @return The total length printed, or -1 on failure.
*/
int	print_hexa_flags(unsigned long num, t_flags *flags, int caps)
{
	int		len;
	char	*s;

	if (num == 0 && flags->truncate == 0)
		return (print_num_zero(flags));
	if (caps)
		s = ft_uitobase(num, "0123456789ABCDEF");
	else
		s = ft_uitobase(num, "0123456789abcdef");
	if (!s)
		return (-1);
	len = ft_strlen(s);
	if (s[0] == '0' && s[1] == '\0')
		flags->format = 0;
	if (flags->left_justify)
		return (print_hexa_justify(s, flags, caps));
	else if (flags->truncate > -1)
		return (print_hexa_truncate(s, flags, caps));
	else if (flags->fill_zero && flags->width > len)
		return (print_hexa_zero(s, flags, caps));
	else if (flags->width - len > 0)
		return (print_hexa_width(s, flags, caps));
	if (flags->format)
		return (print_hexa_format_str(s, caps));
	return (ft_print_str(s, 1));
}
