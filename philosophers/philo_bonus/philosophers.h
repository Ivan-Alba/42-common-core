/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   philosophers.h                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/05/18 03:50:29 by igarcia2          #+#    #+#             */
/*   Updated: 2024/07/15 14:51:51 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#ifndef PHILOSOPHERS_H
# define PHILOSOPHERS_H

# include <unistd.h>
# include <limits.h>
# include <stdlib.h>
# include <sys/time.h>
# include <sys/types.h>
# include <sys/wait.h>
# include <stdio.h>
# include <semaphore.h>
# include <pthread.h>
# include <signal.h>
# include <fcntl.h>

/*
** @brief  Structure representing an isolated philosopher child process context.
** @param  thread: POSIX thread handle for per-process monitoring routine.
** @param  id: Numerical identity of the philosopher (1-indexed).
** @param  pid: Process identifier returned by fork().
** @param  meals_eaten: Counter tracking total meals consumed within process.
** @param  last_meal: Timestamp in ms recorded at last eating action.
** @param  die_time: Starvation threshold in ms.
** @param  eat_time: Duration spent eating in ms.
** @param  sleep_time: Duration spent sleeping in ms.
** @param  act_time: Temporary timing variable for state transitions.
** @param  n_times_eat: Target meal completion count quota (-1 if unbounded).
** @param  start: Pointer to shared global simulation start timestamp.
** @param  forks_sem: Pointer to named POSIX counting semaphore for forks pool.
** @param  write_sem: Pointer to named POSIX binary semaphore for console log.
** @param  last_meal_sem: Named POSIX binary semaphore protecting last_meal.
** @param  meals_eaten_sem: Named POSIX binary semaphore protecting meals_eaten.
*/
typedef struct s_philo
{
	pthread_t	thread;
	int			id;
	int			pid;
	int			meals_eaten;
	long		last_meal;
	long		die_time;
	long		eat_time;
	long		sleep_time;
	long		act_time;
	int			n_times_eat;
	long		*start;
	sem_t		*forks_sem;
	sem_t		*write_sem;
	sem_t		*last_meal_sem;
	sem_t		*meals_eaten_sem;
}	t_philo;

/*
** @brief  Global environment structure managing multiprocessing resources.
** @param  philo_num: Total number of philosopher processes and forks.
** @param  start_time: Global timestamp marking synchronized simulation start.
** @param  forks_sem: Central POSIX named counting semaphore representing forks.
** @param  write_sem: Central POSIX named binary semaphore for terminal IO.
** @param  start_sem: Central POSIX named binary semaphore barrier for start.
** @param  philos: Array allocation containing all individual t_philo contexts.
*/
typedef struct s_data
{
	int			philo_num;
	long		start_time;
	sem_t		*forks_sem;
	sem_t		*write_sem;
	sem_t		*start_sem;
	t_philo		*philos;
}	t_data;

/* Memory, Error & Time Utilities (utils.c) */
int		ft_strlen(char *str);
int		ft_strncmp(const char *s1, const char *s2);
void	print_error(char *str, t_data *data);
void	free_data(t_data *data);
long	get_time_ms(void);

/* Lifecycle & Execution Launchers (run.c) */
int		philos_start(t_data *data);

/* Asynchronous Supervision (monitor.c) */
void	*monitoring(void *param);

/* State Machine & Action Loggers (actions.c) */
void	philo_eat(t_philo *philo);
void	philo_sleep(t_philo *philo);
void	philo_think(t_philo *philo);
void	print_log(char *message, long action_time, t_philo *philo);

/* Inter-Process Management & Semaphore Utilities (proccess_utils.c) */
void	unlink_sem(void);
void	kill_all_proccesses(t_data *data, int error_code);
void	status_info(int status);
void	init_philos_sem(t_philo *philo);

#endif
