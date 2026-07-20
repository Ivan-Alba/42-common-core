/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_free_bonus.c                                    :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/01 16:52:32 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/01 18:25:34 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "ft_printf.h"

/*
** @brief  Safely deallocates memory of a string and returns an error code.
** @param  s: The dynamically allocated string to be freed.
** @return Always returns -1 to signal a stream or system failure.
*/
int	free_and_out(char *s)
{
	free(s);
	return (-1);
}
