/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   parse_map.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: mirifern <mirifern@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/02/02 01:35:11 by mirifern          #+#    #+#             */
/*   Updated: 2025/02/02 01:35:14 by mirifern         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/error.h"

void	normalize_map(t_data *data, char **map)
{
	int		i;
	char	*new_row;
	int		len;

	i = 0;
	while (map[i])
	{
		len = (int)ft_strlen(map[i]);
		if (len != data->map_data->max_width)
		{
			new_row = malloc(data->map_data->max_width + 1);
			malloc_protection(new_row, data);
			ft_memcpy(new_row, map[i], len);
			ft_memset(new_row + len, ' ', data->map_data->max_width - len);
			new_row[data->map_data->max_width] = '\0';
			free(map[i]);
			map[i] = new_row;
		}
		i++;
	}
}

void	parse_map(t_data *data, char **map)
{
	int		i;
	int		j;
	int		k;
	char	*line_trimmed;

	i = -1;
	while (map[++i])
	{
		if (only_spaces(map[i]) || map[i][0] == '\n')
		{
			j = i - 1;
			while (map[++j])
			{
				k = -1;
				if (map[j][++k] != '\n')
					ft_error_exit(ERR_MAP, data);
			}
		}
		line_trimmed = ft_strtrim(map[i], "\n");
		data->map_data->map = add_to_array(&data->map_data->map, line_trimmed);
		free(line_trimmed);
	}
	if (!data->map_data->map)
		ft_error_exit(ERR_MAP, data);
}
