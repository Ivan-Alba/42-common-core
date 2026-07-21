/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   execute.c                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/05/14 21:00:27 by igarcia2          #+#    #+#             */
/*   Updated: 2024/05/22 17:38:43 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "pipex.h"

/*
** @brief  Redirects standard input to either the input file or the
**         previous pipe.
** @param  data: Pointer to the main pipex data structure.
** @param  i: Index of the current command to execute.
*/
void	set_stdin(t_pipex *data, int i)
{
	int	fd;

	if (data->cmds[i].first)
	{
		if (data->in_file)
		{
			fd = open(data->in_file, O_RDONLY);
			if (dup2(fd, STDIN_FILENO) == -1)
				perror("Dup Error");
			close(fd);
		}
		else
			exit(1);
	}
	else
	{
		if (dup2(data->pipes[(i + data->is_heredoc) * 2 - 2],
				STDIN_FILENO) == -1)
			perror("Dup Error");
	}
}

/*
** @brief  Redirects standard output to either the output file or the next pipe.
** @param  data: Pointer to the main pipex data structure.
** @param  i: Index of the current command to execute.
*/
void	set_stdout(t_pipex *data, int i)
{
	int	fd;

	if (data->cmds[i].last)
	{
		if (data->out_file)
		{
			fd = open(data->out_file, O_WRONLY | O_APPEND);
			if (dup2(fd, STDOUT_FILENO) == -1)
				perror("Dup Error");
			close(fd);
		}
		else
			exit(1);
	}
	else
	{
		if (dup2(data->pipes[(i + data->is_heredoc) * 2 + 1],
				STDOUT_FILENO) == -1)
			perror("Dup Error");
	}
}

/*
** @brief  Configures input/output file descriptors and executes command
**         via execve.
** @param  data: Pointer to the main pipex data structure.
** @param  i: Index of the current command to execute.
*/
void	execute(t_pipex *data, int i)
{
	set_stdin(data, i);
	set_stdout(data, i);
	free_close_pipes(data);
	if (data->cmds[i].cmd_flags[0] && data->cmds[i].cmd_flags[0][0] == '/'
			&& access(data->cmds[i].path, X_OK) == -1)
	{
		perror(data->cmds[i].path);
		exit(127);
	}
	if (execve(data->cmds[i].path, data->cmds[i].cmd_flags, data->env) == -1)
	{
		if (data->cmds[i].cmd_flags[0])
		{
			write(2, data->cmds[i].cmd_flags[0],
				ft_strlen(data->cmds[i].cmd_flags[0]));
		}
		write(2, ": command not found\n", 20);
		exit(1);
	}
}
