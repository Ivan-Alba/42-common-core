/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   get_next_line_utils.c                              :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/05 18:12:06 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/05 19:57:16 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "get_next_line.h"

/*
** @brief  Scans the given string to look for a newline character.
** @param  str: Pointer to the null-terminated string to be checked.
** @return 1 if a newline '\n' is found, 0 otherwise or if str is NULL.
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
** @brief  Frees the memory allocation of a string pointer and nulls it out.
** @param  str: Double pointer to the allocated memory area to clear.
** @return Always returns NULL to clean up return flows in parent functions.
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
** @brief  Verifies if text remains in saved after an index, trimming or
**         clearing.
** @param  saved: Double pointer to the static accumulated data stack.
** @param  i: The index position tracking the end of the extracted line chunk.
** @param  res: Double pointer to the line result to clear if allocation fails.
** @return 1 on successful extraction management, or 0 on sub-allocation error.
*/
int	check_stack(char **saved, int i, char **res)
{
	if ((*saved)[i] != '\0')
	{
		*saved = cut_after_next_line(&*saved);
		if (!*saved)
			return ((int)free_and_out(&*res));
	}
	else
	{
		free(*saved);
		*saved = NULL;
	}
	return (1);
}
