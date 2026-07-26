/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   utils.c                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/05/18 03:59:20 by igarcia2          #+#    #+#             */
/*   Updated: 2024/07/15 12:30:37 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "philosophers.h"

/*
** @brief  Calculates the character length of a null-terminated string.
** @param  str: String input pointer.
** @return Integer length of the string.
*/
int	ft_strlen(char *str)
{
	int	i;

	i = 0;
	while (str[i])
		i++;
	return (i);
}

/*
** @brief  Writes error message to stderr, frees memory, and unlinks semaphores.
** @param  str: Error description string to display.
** @param  data: Pointer to global simulation environment structure.
*/
void	print_error(char *str, t_data *data)
{
	if (str)
		write(2, str, ft_strlen(str));
	if (data)
		free_data(data);
	unlink_sem();
}

/*
** @brief  Frees heap memory allocated for structure and unlinks system
**         semaphores.
** @param  data: Pointer to global simulation environment structure.
*/
void	free_data(t_data *data)
{
	if (data)
	{
		if (data->philos)
		{
			free(data->philos);
		}
		free(data);
	}
	unlink_sem();
}

/*
** @brief  Compares two character strings up to matching boundary.
** @param  s1: First target string.
** @param  s2: Second target string.
** @return 0 if strings match, difference between non-matching bytes otherwise.
*/
int	ft_strncmp(const char *s1, const char *s2)
{
	unsigned char	*c1;
	unsigned char	*c2;
	int				i;

	i = 0;
	c1 = (unsigned char *) s1;
	c2 = (unsigned char *) s2;
	while (s1[i] != '\0' && s2[i] != '\0' && s1[i] == s2[i])
		i++;
	return (c1[i] - c2[i]);
}

/*
** @brief  Retrieves current UNIX timestamp converted to milliseconds.
** @return Milliseconds elapsed since Epoch (1970-01-01 00:00:00 UTC).
*/
long	get_time_ms(void)
{
	struct timeval	tv;

	gettimeofday(&tv, NULL);
	return ((tv.tv_sec * 1000L) + (tv.tv_usec / 1000L));
}
