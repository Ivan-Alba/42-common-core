/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   proccess_utils.c                                   :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/07/16 12:09:00 by igarcia2          #+#    #+#             */
/*   Updated: 2024/07/16 12:09:01 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "philosophers.h"

/*
** @brief  Unlinks named POSIX semaphores from filesystem to prevent leakage.
*/
void	unlink_sem(void)
{
	sem_unlink("/last_meal_sem");
	sem_unlink("/meals_eaten_sem");
	sem_unlink("/forks_sem");
	sem_unlink("/write_sem");
	sem_unlink("/start_sem");
}

/*
** @brief  Terminates all child processes using SIGKILL upon failure or death.
** @param  data: Pointer to global simulation environment structure.
** @param  error_code: ID of philosopher process that triggered termination.
*/
void	kill_all_proccesses(t_data *data, int error_code)
{
	int	i;

	i = -1;
	while (++i < data->philo_num)
	{
		if (i + 1 != error_code)
			kill(data->philos[i].pid, SIGKILL);
	}
}

/*
** @brief  Utility debugging function logging process exit status details.
** @param  status: Integer status mask returned by waitpid execution.
*/
void	status_info(int status)
{
	int	exit_status;
	int	term_signal;

	if (WIFEXITED(status))
	{
		exit_status = WEXITSTATUS(status);
		printf("El proceso hijo terminó normalmente.\n");
		printf("Estado de salida del hijo: %d\n", exit_status);
	}
	else if (WIFSIGNALED(status))
	{
		term_signal = WTERMSIG(status);
		printf("El proceso hijo terminó debido a una señal.\n");
		printf("Señal que terminó el hijo: %d\n", term_signal);
	}
	else
	{
		printf("El proceso hijo terminó de manera anormal.\n");
	}
}

/*
** @brief  Opens individual process-level semaphores for state protection.
** @param  philo: Pointer to individual philosopher context structure.
*/
void	init_philos_sem(t_philo *philo)
{
	philo->last_meal_sem = sem_open("/last_meal_sem", O_CREAT, 0644, 1);
	philo->meals_eaten_sem = sem_open("/meals_eaten_sem", O_CREAT, 0644, 1);
}
