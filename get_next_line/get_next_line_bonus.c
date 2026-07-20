/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   get_next_line_bonus.c                              :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/07 17:38:35 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/08 19:37:59 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "get_next_line_bonus.h"

/*
** @brief  Trims the static string, removing the extracted line up to '\n'.
** @param  str: Double pointer to the static accumulated string buffer.
** @return The newly allocated remainder string, or NULL on error/empty.
*/
char	*cut_after_next_line(char **str)
{
	int		i;
	int		j;
	char	*res;
	int		x;

	i = 0;
	j = 0;
	while ((*str)[i] != '\n')
		i++;
	while ((*str)[i + 1 + j] != '\0')
		j++;
	if (!j)
		return (free_and_out(&*str));
	res = (char *) malloc(j + 1 * sizeof(char));
	if (!res)
		return (free_and_out(&*str));
	x = -1;
	while (++x < j)
		res[x] = (*str)[i + 1 + x];
	res[x] = '\0';
	free(*str);
	return (res);
}

/*
** @brief  Scans the accumulated buffer to extract a single line up to '\n'.
** @param  saved: Double pointer to the static accumulated string buffer.
** @return The allocated line string containing '\n', or NULL on error.
*/
char	*search_for_next_line(char **saved)
{
	int		i;
	int		j;
	char	*res;

	i = 0;
	j = -1;
	while ((*saved)[i] != '\0' && (*saved)[i] != '\n')
		i++;
	if ((*saved)[i] == '\n')
	{
		res = malloc((++i + 1) * sizeof(char));
		if (!res)
			return (free_and_out(&*saved));
		while (++j < i)
			res[j] = (*saved)[j];
		res[j] = '\0';
		if (!check_stack(&*saved, i, &res))
			return (NULL);
		return (res);
	}
	return (free_and_out(&*saved));
}

/*
** @brief  Concatenates the current static buffer with newly read chunk.
** @param  saved: Double pointer to the static accumulated string buffer.
** @param  buff: Pointer to the temporary read buffer.
** @param  buff_size: Exact number of bytes successfully read into buff.
** @return A newly allocated combined string, or NULL on allocation error.
*/
char	*ft_strcat(char **saved, char *buff, int buff_size)
{
	char	*res;
	int		i;
	int		j;
	int		s1_len;

	s1_len = 0;
	while (*saved && (*saved)[s1_len] != '\0')
		s1_len++;
	res = (char *) malloc((s1_len + buff_size + 1) * sizeof(char));
	if (!res)
		return (free_and_out(&*saved));
	i = 0;
	j = 0;
	while (*saved && (*saved)[j] != '\0')
		res[i++] = (*saved)[j++];
	j = 0;
	while (buff && j < buff_size)
		res[i++] = buff[j++];
	res[i] = '\0';
	free(*saved);
	return (res);
}

/*
** @brief  Loop reader that fetches chunks from fd until a '\n' is found.
** @param  saved: Double pointer to the static data stack for the given fd.
** @param  fd: The file descriptor target to perform read operations on.
** @return The extracted line up to '\n', or NULL on error or EOF.
*/
char	*read_next_line(char **saved, int fd)
{
	int		readed;
	char	*buffer;

	buffer = malloc (BUFFER_SIZE);
	while (is_next_line(*saved) == 0)
	{
		readed = read(fd, buffer, BUFFER_SIZE);
		if (readed == -1)
		{
			if (*saved)
				free(*saved);
			*saved = NULL;
			return (free_and_out(&buffer));
		}
		if (readed > 0)
		{
			*saved = ft_strcat(&*saved, buffer, readed);
			if (!*saved)
				return (free_and_out(&buffer));
		}
		else if (readed == 0)
			return (free_and_out(&buffer), *saved);
	}
	free_and_out(&buffer);
	return (search_for_next_line(&*saved));
}

/*
** @brief  Main entry point to fetch the next available line from a file.
** @param  fd: The file descriptor to read from.
** @return The line read from fd including '\n', or NULL if empty or error.
*/
char	*get_next_line(int fd)
{
	char			*next_line;
	static t_list	saved[OPEN_MAX + 1];

	if (fd < 0 || fd > OPEN_MAX + 1)
		return (NULL);
	if (saved[fd].line != NULL && is_next_line(saved[fd].line))
	{
		next_line = search_for_next_line(&saved[fd].line);
		if (!next_line)
			return (NULL);
		return (next_line);
	}
	next_line = read_next_line(&saved[fd].line, fd);
	if (!next_line)
		return (NULL);
	else if (next_line && !is_next_line(next_line))
		saved[fd].line = NULL;
	return (next_line);
}
