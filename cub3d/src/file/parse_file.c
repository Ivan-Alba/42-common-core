/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   parse_file.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/02 01:30:57 by mirifern          #+#    #+#             */
/*   Updated: 2025/02/04 23:30:22 by mirifern         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/error.h"
#include "../../inc/file.h"
#include "../../inc/texture.h"

char	*parse_path(char *line, t_data *data, char id)
{
	char	*path;

	path = ft_strtrim(line, " \t");
	if (!path || ft_strchr(path, ' '))
		ft_error_exit(ERR_TXT, data);
	if (id == ID_NORTH || id == ID_SOUTH || id == ID_WEST || id == ID_EAST
		|| id == ID_DOOR || id == ID_LOCKED_DOOR || id == ID_CHEST
		|| id == ID_KEY || id == ID_COIN || id == ID_EXIT)
		try_open_path(data, path);
	else if (id == ID_FLOOR || id == ID_CEIL)
		if (check_color_or_texture(data, path, id) == 1)
			return (free(path), NULL);
	if (id == ID_MAP)
	{
		if (ft_strncmp(path + ft_strlen(path) - 4, ".cub", 4))
			ft_error_exit(ERR_NEXT, data);
	}
	return (path);
}

int	parse_mandatory(t_data *d, char *trim_line)
{
	if (ft_strncmp(trim_line, NORTH_TXT, ft_strlen(NORTH_TXT)) == 0)
		add_texture_node(ID_NORTH, parse_path(trim_line + ft_strlen(NORTH_TXT),
				d, ID_NORTH), d);
	else if (ft_strncmp(trim_line, SOUTH_TXT, ft_strlen(SOUTH_TXT)) == 0)
		add_texture_node(ID_SOUTH, parse_path(trim_line + ft_strlen(SOUTH_TXT),
				d, ID_SOUTH), d);
	else if (ft_strncmp(trim_line, WEST_TXT, ft_strlen(WEST_TXT)) == 0)
		add_texture_node(ID_WEST, parse_path(trim_line + ft_strlen(WEST_TXT),
				d, ID_WEST), d);
	else if (ft_strncmp(trim_line, EAST_TXT, ft_strlen(EAST_TXT)) == 0)
		add_texture_node(ID_EAST, parse_path(trim_line + ft_strlen(EAST_TXT),
				d, ID_EAST), d);
	else if (ft_strncmp(trim_line, FLOOR, ft_strlen(FLOOR)) == 0)
		add_texture_node(ID_FLOOR, parse_path(trim_line + ft_strlen(FLOOR),
				d, ID_FLOOR), d);
	else if (ft_strncmp(trim_line, CEIL, ft_strlen(CEIL)) == 0)
		add_texture_node(ID_CEIL, parse_path(trim_line + ft_strlen(CEIL),
				d, ID_CEIL), d);
	else
		return (1);
	return (0);
}

int	parse_extras(t_data *d, char *trim_line)
{
	if (ft_strncmp(trim_line, DOOR, ft_strlen(DOOR)) == 0)
		add_texture_node(ID_DOOR, parse_path(trim_line + ft_strlen(DOOR),
				d, ID_DOOR), d);
	else if (ft_strncmp(trim_line, LOCKED_DOOR, ft_strlen(LOCKED_DOOR)) == 0)
		add_texture_node(ID_LOCKED_DOOR, parse_path(trim_line
				+ ft_strlen(LOCKED_DOOR), d, ID_LOCKED_DOOR), d);
	else if (ft_strncmp(trim_line, EXIT, ft_strlen(EXIT)) == 0)
		add_texture_node(ID_EXIT, parse_path(trim_line + ft_strlen(EXIT),
				d, ID_EXIT), d);
	else if (ft_strncmp(trim_line, NEXT_MAP, ft_strlen(NEXT_MAP)) == 0)
		d->map_data->next_map = parse_path(trim_line + ft_strlen(NEXT_MAP),
				d, ID_MAP);
	else if (ft_strncmp(trim_line, TREE, ft_strlen(TREE)) == 0)
		add_texture_node(ID_TREE, parse_path(trim_line + ft_strlen(TREE),
				d, ID_TREE), d);
	else
		return (1);
	return (0);
}

int	parse_line(t_data *data, char *line)
{
	char	*trim_line;

	trim_line = ft_strtrim(line, "\t\n ");
	if (BONUS)
	{
		if (parse_mandatory(data, trim_line) == 0
			|| parse_extras(data, trim_line) == 0)
			return (free(trim_line), 0);
	}
	else
	{
		if (parse_mandatory(data, trim_line) == 0)
			return (free(trim_line), 0);
	}
	return (free(trim_line), 1);
}

void	parse_cub_file(t_data *data, char **cub_file)
{
	int	i;

	i = 0;
	while (cub_file[i])
	{
		if (ft_strlen(cub_file[i]) == 0 || only_spaces(cub_file[i]))
			i++;
		else
		{
			if (parse_line(data, cub_file[i++]) == 1)
			{
				i--;
				break ;
			}
		}
	}
	check_paths_qt(data);
	parse_map(data, cub_file + i);
}
