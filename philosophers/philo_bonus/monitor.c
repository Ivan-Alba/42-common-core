/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   monitor.c                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/07/16 12:08:50 by igarcia2          #+#    #+#             */
/*   Updated: 2024/07/16 12:08:52 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "philosophers.h"

/*
** @brief  Verifies if child philosopher process satisfied meal requirement
**         quota.
** @param  philo: Pointer to individual philosopher context structure.
*/
void	check_if_meals_eaten(t_philo *philo)
{
	sem_wait(philo->meals_eaten_sem);
	if (philo->meals_eaten >= philo->n_times_eat)
	{
		sem_post(philo->meals_eaten_sem);
		exit(0);
		return ;
	}
	sem_post(philo->meals_eaten_sem);
}

/*
** @brief  Inspects philosopher starvation limit and exits process on death.
** @param  philo: Pointer to individual philosopher context structure.
*/
void	check_if_dead(t_philo *philo)
{
	sem_wait(philo->last_meal_sem);
	if (get_time_ms() - philo->last_meal > philo->die_time)
	{
		sem_post(philo->last_meal_sem);
		print_log("is dead", get_time_ms(), philo);
		exit(philo->id);
	}
	else
		sem_post(philo->last_meal_sem);
}

/*
** @brief  Per-process thread supervisor routine checking starvation and quota.
** @param  param: Generic pointer cast to individual t_philo structure.
** @return Always NULL upon thread completion.
*/
void	*monitoring(void *param)
{
	t_philo	*philo;

	philo = (t_philo *)param;
	while (1)
	{
		if (philo->n_times_eat > -1)
			check_if_meals_eaten(philo);
		check_if_dead(philo);
		usleep(200);
	}
	return (NULL);
}
