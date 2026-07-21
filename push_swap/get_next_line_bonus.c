/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   get_next_line_bonus.c                              :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/22 19:05:59 by igarcia2          #+#    #+#             */
/*   Updated: 2024/08/07 12:08:37 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "get_next_line_bonus.h"

/*
** @brief  Trims a string up to the newline character and keeps the rest.
** @param  str: Double pointer to the target string buffer.
** @return Pointer to the allocated remaining substring, or NULL if empty.
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
** @brief  Extracts a complete line including the terminating newline character.
** @param  saved: Double pointer to the static accumulator buffer.
** @return Allocated string containing the extracted line, or NULL on error.
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
** @brief  Concatenates saved buffer string with newly read input data.
** @param  saved: Pointer to existing accumulated buffer string.
** @param  buff: Read block buffer containing fresh content bytes.
** @param  buff_size: Exact number of bytes successfully read into memory.
** @return Reallocated string with combined content, or NULL on failure.
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
** @brief  Reads chunks from disk until a full line or EOF is reached.
** @param  saved: Double pointer to the persistent accumulator buffer.
** @param  fd: Target file descriptor to read input stream from.
** @return Extracted full line string, raw unparsed buffer, or NULL.
*/
char	*read_next_line(char **saved, int fd)
{
	int		readed;
	char	*buffer;

	buffer = malloc(BUFFER_SIZE);
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
** @brief  Reads and returns a line from the specified file descriptor.
** @param  fd: Target file descriptor stream index to read from.
** @return Allocated string containing the line, or NULL on EOF or error.
*/
char	*get_next_line(int fd)
{
	char			*next_line;
	static t_static	saved[1024];

	if (fd < 0 || fd > 1024 + 1)
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
