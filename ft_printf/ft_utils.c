/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_utils.c                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/21 15:08:36 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/01 16:23:44 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "ft_printf.h"

/*
** @brief  Converts an unsigned int into a dynamically
**         allocated string representation based on a base.
** @param  num: The unsigned integer value to convert.
** @param  base: The character array for the numerical base.
** @return A pointer to the allocated string, or NULL if fails.
*/
char	*ft_uitobase(unsigned int num, char *base)
{
	int				len;
	unsigned int	n;
	char			*res;

	if (num == 0)
		return (ft_strdup("0"));
	n = num;
	len = 0;
	while (n != 0)
	{
		len++;
		n = n / ft_strlen(base);
	}
	res = (char *) malloc ((len + 1) * sizeof(char));
	if (!res)
		return (NULL);
	res[len--] = '\0';
	while (num != 0)
	{
		res[len--] = base[num % ft_strlen(base)];
		num /= ft_strlen(base);
	}
	return (res);
}

/*
** @brief  Duplicates a string or produces a fallback
**         representation for NULL inputs.
** @param  s: The original string pointer to be duplicated.
** @return A pointer to the newly allocated string representation,
**         or NULL if the allocation fails.
*/
char	*ft_read_string(char *s)
{
	char	*content;

	content = NULL;
	if (!s)
		content = ft_strdup("(null)");
	else
		content = ft_strdup(s);
	if (!content)
		return (NULL);
	return (content);
}

/*
** @brief  Outputs a string to a file descriptor and safely
**         deallocates its buffer memory.
** @param  s: The dynamically allocated string to print and free.
** @param  fd: The file descriptor target for the output stream.
** @return The total length printed on success, or -1 if the
**         system write fails (triggering cleanup).
*/
int	ft_print_str(char *s, int fd)
{
	int	len;

	if (!s)
		return (-1);
	len = ft_strlen(s);
	if (write(fd, s, len) == -1)
		return (free_and_out(s));
	free(s);
	return (len);
}

/*
** @brief  Calculates the digit length of a memory address
**         when converted to hexadecimal format.
** @param  num: The unsigned pointer-sized integer value.
** @return The total amount of numeric hex characters required.
*/
static int	ptr_length(uintptr_t num)
{
	int	len;

	len = 0;
	while (num != 0)
	{
		len++;
		num = num / 16;
	}
	return (len);
}

/*
** @brief  Converts a raw memory address into a fully formatted
**         hexadecimal layout string. Explicitly catches NULL
**         pointers to produce the standard "(nil)" output.
** @param  num: The raw pointer address cast to uintptr_t.
** @param  base: The character array for the hex dictionary.
** @return A pointer to the allocated hex layout string,
**         or NULL if malloc fails.
*/
char	*ft_read_ptr(uintptr_t num, char *base)
{
	int			len;
	char		*res;
	uintptr_t	x;

	if (!num)
		return (ft_strdup("(nil)"));
	x = num;
	len = ptr_length(num) + 2;
	res = (char *) malloc((len + 1) * sizeof(char));
	if (!res)
		return (NULL);
	res[len--] = '\0';
	while (x != 0)
	{
		res[len--] = base[x % 16];
		x /= 16;
	}
	res[1] = 'x';
	res[0] = '0';
	return (res);
}
