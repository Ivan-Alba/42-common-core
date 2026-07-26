/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   actions.c                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/07/16 12:08:40 by igarcia2          #+#    #+#             */
/*   Updated: 2024/07/17 11:24:23 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "philosophers.h"

/*
** @brief  Process-safe logger printing state changes using named semaphores.
** @param  txt: Action message to be logged on standard output.
** @param  action_time: Current timestamp in milliseconds.
** @param  philo: Pointer to philosopher structure.
*/
void	print_log(char *txt, long action_time, t_philo *philo)
{
	sem_wait(philo->write_sem);
	printf("%ld %d %s\n", action_time - *(philo->start), philo->id, txt);
	if (ft_strncmp(txt, "is dead") != 0)
		sem_post(philo->write_sem);
}

/*
** @brief  Acquires two forks from central semaphore pool, updates meal state,
**         and releases.
** @param  philo: Pointer to philosopher structure.
*/
void	philo_eat(t_philo *philo)
{
	sem_wait(philo->forks_sem);
	print_log("has taken a fork", get_time_ms(), philo);
	sem_wait(philo->forks_sem);
	print_log("has taken a fork", get_time_ms(), philo);
	sem_wait(philo->last_meal_sem);
	philo->act_time = get_time_ms();
	philo->last_meal = philo->act_time;
	sem_post(philo->last_meal_sem);
	print_log("is eating", philo->act_time, philo);
	while (1)
	{
		if (get_time_ms() - philo->act_time >= philo->eat_time)
			break ;
		usleep(100);
	}
	sem_post(philo->forks_sem);
	sem_post(philo->forks_sem);
	sem_wait(philo->meals_eaten_sem);
	philo->meals_eaten++;
	sem_post(philo->meals_eaten_sem);
}

/*
** @brief  Executes sleeping sequence for specified duration using
**         precise polling.
** @param  philo: Pointer to philosopher structure.
*/
void	philo_sleep(t_philo *philo)
{
	philo->act_time = get_time_ms();
	print_log("is sleeping", philo->act_time, philo);
	while (1)
	{
		if (get_time_ms() - philo->act_time >= philo->sleep_time)
			break ;
		usleep(100);
	}
}

/*
** @brief  Logs thinking status before attempting next semaphore fork
**         acquisition.
** @param  philo: Pointer to philosopher structure.
*/
void	philo_think(t_philo *philo)
{
	philo->act_time = get_time_ms();
	print_log("is thinking", philo->act_time, philo);
}
