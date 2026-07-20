/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_printf.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/17 19:47:48 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/30 17:27:37 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "ft_printf.h"

/*
** @brief  Outputs a single character to the given file descriptor.
** @param  c: The character to write.
** @param  fd: The target file descriptor.
** @return Returns 1 on success, or -1 if the system write fails.
*/
static int	ft_putchar_len(char c, int fd)
{
	if (write(fd, &c, 1) == -1)
		return (-1);
	return (1);
}

/*
** @brief  Evaluates the mandatory format type and routes execution.
** @param  s: Pointer to the specific format specifier character.
** @param  args: The va_list containing the variadic arguments.
** @return The total length successfully printed, or -1 on failure.
*/
int	ft_check_args(const char *s, va_list args)
{
	char	*content;

	content = NULL;
	if (*s == 's')
		content = ft_read_string(va_arg(args, char *));
	else if (*s == 'c')
		return (ft_putchar_len(va_arg(args, unsigned int), 1));
	else if (*s == 'p')
		content = ft_read_ptr((uintptr_t) va_arg(args, void *),
				"0123456789abcdef");
	else if (*s == 'd')
		content = ft_itoa(va_arg(args, int));
	else if (*s == 'i')
		content = ft_itoa(va_arg(args, int));
	else if (*s == 'u')
		content = ft_utoa(va_arg(args, unsigned int));
	else if (*s == 'x')
		content = ft_uitobase(va_arg(args, unsigned int), "0123456789abcdef");
	else if (*s == 'X')
		content = ft_uitobase(va_arg(args, unsigned int), "0123456789ABCDEF");
	else if (*s == '%')
		return (ft_putchar_len('%', 1));
	return (ft_print_str(content, 1));
}

/*
** @brief  The primary printf clone engine mimicking the libc behavior.
** @param  s: The initial format string containing specifiers and text.
** @return The total amount of bytes successfully written to stdout.
*/
int	ft_printf(const char *s, ...)
{
	va_list	args;
	int		total;
	int		sum;

	va_start(args, s);
	if (!s || !*s)
		return (0);
	total = 0;
	while (*s != '\0')
	{
		if (*s == '%' && (ft_strchr("cspdiuxX%", *(s + 1))))
			sum = ft_check_args(++s, args);
		else if (*s == '%')
			sum = ft_check_flags(&s, args);
		else
			sum = write(1, s, 1);
		if (sum == -1)
			return (-1);
		s++;
		total += sum;
	}
	va_end(args);
	return (total);
}
