/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   read_map.c                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/26 14:39:19 by igarcia2          #+#    #+#             */
/*   Updated: 2024/04/04 17:12:26 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fdf.h"

/*
** @brief  Reads a map file line by line and splits it into a 2D string array.
** @param  file: Path to the map file.
** @return Pointer to a NULL-terminated array of strings containing lines.
*/
char	**read_map(char *file)
{
	int		fd;
	char	*content;
	char	*all_content;
	char	**res;

	fd = open(file, O_RDONLY);
	if (fd < 0)
		exit_error(FILE_ERROR);
	content = get_next_line(fd);
	all_content = NULL;
	if (!content)
		exit_error(MAP_ERROR);
	while (content)
	{
		all_content = ft_strcat(&all_content, content, ft_strlen(content));
		free(content);
		content = get_next_line(fd);
	}
	free(content);
	res = ft_split(all_content, '\n');
	free(all_content);
	if (!res)
		exit_error(SPLIT_ERROR);
	return (res);
}
