/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   actions.c                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/07/16 12:07:39 by igarcia2          #+#    #+#             */
/*   Updated: 2024/07/17 11:22:42 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "philosophers.h"

/*
** @brief  Thread-safe logger printing state changes with relative timestamps.
** @param  txt: Action message to be logged on standard output.
** @param  action_time: Current timestamp in milliseconds.
** @param  philo: Pointer to philosopher structure.
*/
void	print_log(char *txt, long action_time, t_philo *philo)
{
	int	dead;

	pthread_mutex_lock(philo->write_lock);
	pthread_mutex_lock(philo->dead_lock);
	dead = *philo->dead;
	pthread_mutex_unlock(philo->dead_lock);
	if (!dead || !ft_strncmp(txt, "is dead"))
		printf("%ld %d %s\n", action_time - *(philo->start), philo->id, txt);
	pthread_mutex_unlock(philo->write_lock);
}

/*
** @brief  Locks left and right forks preventing deadlocks via even/odd logic.
** @param  philo: Pointer to philosopher structure.
** @param  is_first_fork: Flag indicating whether picking up first or
**         second fork.
*/
void	lock_forks(t_philo *philo, int is_first_fork)
{
	if (is_first_fork)
	{
		if (philo->id % 2 == 0)
			pthread_mutex_lock(philo->l_fork);
		else
			pthread_mutex_lock(&(philo->r_fork));
		print_log("has taken a fork", get_time_ms(), philo);
	}
	else
	{
		if (philo->id % 2 == 0)
			pthread_mutex_lock(&(philo->r_fork));
		else
			pthread_mutex_lock(philo->l_fork);
		print_log("has taken a fork", get_time_ms(), philo);
	}
}

/*
** @brief  Executes eating sequence, updates meal timestamps, and unlocks forks.
** @param  philo: Pointer to philosopher structure.
*/
void	philo_eat(t_philo *philo)
{
	lock_forks(philo, 1);
	if (philo->l_fork)
	{
		lock_forks(philo, 0);
		pthread_mutex_lock(&(philo->meal_lock));
		philo->act_time = get_time_ms();
		philo->last_meal = philo->act_time;
		pthread_mutex_unlock(&(philo->meal_lock));
		print_log("is eating", philo->act_time, philo);
		while (1)
		{
			if (get_time_ms() - philo->act_time >= philo->eat_time)
				break ;
			usleep(100);
		}
		pthread_mutex_unlock(&(philo->r_fork));
		pthread_mutex_unlock(philo->l_fork);
	}
	else
	{
		usleep(2000);
		pthread_mutex_unlock(&(philo->r_fork));
	}
}

/*
** @brief  Executes sleeping sequence for specified duration or single-philo
**         edge case.
** @param  philo: Pointer to philosopher structure.
*/
void	philo_sleep(t_philo *philo)
{
	if (philo->l_fork)
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
	else
		usleep(philo->die_time * 1001);
}

/*
** @brief  Logs thinking status prior to attempting next fork acquisition.
** @param  philo: Pointer to philosopher structure.
*/
void	philo_think(t_philo *philo)
{
	if (philo->l_fork)
	{
		philo->act_time = get_time_ms();
		print_log("is thinking", philo->act_time, philo);
	}
}
