/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_putchar_fd.c                                    :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/12 17:44:21 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/12 21:31:33 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Outputs the character 'c' to the given file descriptor.
** @param  c: The character to output.
** @param  fd: The file descriptor on which to write.
** @return None.
*/
void	ft_putchar_fd(char c, int fd)
{
	write(fd, &c, 1);
}
