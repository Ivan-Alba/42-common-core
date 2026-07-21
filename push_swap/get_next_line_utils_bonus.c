/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   get_next_line_utils_bonus.c                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/22 19:06:29 by igarcia2          #+#    #+#             */
/*   Updated: 2024/08/07 12:11:44 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "get_next_line_bonus.h"

/*
** @brief  Checks whether a given string contains a newline character.
** @param  str: Target string to inspect.
** @return 1 if newline is found, 0 if not or if string is NULL.
*/
int	is_next_line(char *str)
{
	int	i;

	if (!str)
		return (0);
	i = 0;
	while (str[i] != '\0')
	{
		if (str[i] == '\n')
			return (1);
		i++;
	}
	return (0);
}

/*
** @brief  Frees allocated string memory, sets pointer to NULL, and returns NULL.
** @param  str: Double pointer to the string buffer to free.
** @return Always returns NULL.
*/
char	*free_and_out(char **str)
{
	if (*str)
	{
		free(*str);
		*str = NULL;
	}
	return (NULL);
}

/*
** @brief  Manages remaining memory buffer after a full line has been extracted.
** @param  saved: Double pointer to the persistent accumulator buffer.
** @param  i: Index offset where the newline was identified.
** @param  res: Double pointer to the extracted line result string.
** @return 1 on successful extraction/cleanup, 0 on memory allocation error.
*/
int	check_stack(char **saved, int i, char **res)
{
	if ((*saved)[i] != '\0')
	{
		*saved = cut_after_next_line(&*saved);
		if (!*saved)
			return (free_and_out(&*res), 0);
	}
	else
	{
		free(*saved);
		*saved = NULL;
	}
	return (1);
}
