/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   philosophers.h                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/05/18 03:50:29 by igarcia2          #+#    #+#             */
/*   Updated: 2024/08/07 12:46:08 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#ifndef PHILOSOPHERS_H
# define PHILOSOPHERS_H

# include <unistd.h>
# include <limits.h>
# include <stdlib.h>
# include <sys/time.h>
# include <stdio.h>
# include <pthread.h>

# define MIN_VALUES_ERROR "Times must be at least 60ms\n"
# define MALLOC_ERROR "Error allocating memory\n"
# define ARGS_NUM_ERROR "Error: there must be 4 or 5 numerical arguments\n"
# define ARGS_ERROR "Error: arguments must be numerical and cannot be 0\n"

/*
** @brief  Structure representing an individual philosopher's context and locks.
** @param  thread: POSIX execution thread handle.
** @param  id: Numerical identity of the philosopher (1-indexed).
** @param  meals_eaten: Counter tracking total meals consumed.
** @param  last_meal: Timestamp in ms recorded at last eating action.
** @param  die_time: Starvation threshold in ms.
** @param  eat_time: Duration spent eating in ms.
** @param  sleep_time: Duration spent sleeping in ms.
** @param  act_time: Temporary timing variable for state transitions.
** @param  n_times_eat: Target meal completion count quota (-1 if unbounded).
** @param  dead: Pointer to shared global simulation termination flag.
** @param  start: Pointer to shared global simulation start timestamp.
** @param  r_fork: Dedicated right fork mutex instance owned by this philo.
** @param  l_fork: Pointer referencing neighbor philosopher's right fork mutex.
** @param  write_lock: Pointer to shared output stream mutex.
** @param  dead_lock: Pointer to shared termination flag guard mutex.
** @param  start_lock: Pointer to shared initial thread synchronization mutex.
** @param  meal_lock: Mutex protecting last_meal state timestamp.
** @param  meals_eaten_lock: Mutex protecting meals_eaten completion counter.
*/
typedef struct s_philo
{
	pthread_t		thread;
	int				id;
	int				meals_eaten;
	long			last_meal;
	long			die_time;
	long			eat_time;
	long			sleep_time;
	long			act_time;
	int				n_times_eat;
	int				*dead;
	long			*start;
	pthread_mutex_t	r_fork;
	pthread_mutex_t	*l_fork;
	pthread_mutex_t	*write_lock;
	pthread_mutex_t	*dead_lock;
	pthread_mutex_t	*start_lock;
	pthread_mutex_t	meal_lock;
	pthread_mutex_t	meals_eaten_lock;
}	t_philo;

/*
** @brief  Global environment structure managing simulation resources and state.
** @param  dead_flag: Shared state variable signaling simulation termination.
** @param  philo_num: Total number of philosophers and forks in the system.
** @param  start_time: Global timestamp marking synchronized simulation start.
** @param  dead_lock: Global mutex serializing termination status updates.
** @param  start_lock: Global barrier mutex synchronizing thread launch.
** @param  write_lock: Global mutex preventing interleaved console logging.
** @param  philos: Array allocation containing all individual t_philo contexts.
*/
typedef struct s_data
{
	int				dead_flag;
	int				philo_num;
	long			start_time;
	pthread_mutex_t	dead_lock;
	pthread_mutex_t	start_lock;
	pthread_mutex_t	write_lock;
	t_philo			*philos;
}	t_data;

/* Initialization & Main Router Functions */
void	init_data(t_data *data, int argc, char **argv);

/* Argument Validation Routines (check_args.c) */
int		check_int(char *arg);
int		check_args(int argc, char **argv, t_data *data);
int		check_min_values(t_data *data);

/* Memory, Error & Time Utilities (utils.c) */
int		ft_strlen(char *str);
int		ft_strncmp(const char *s1, const char *s2);
void	print_error(char *str, t_data *data);
void	free_data(t_data *data);
long	get_time_ms(void);

/* Lifecycle & Execution Launchers (run.c) */
void	philos_start(t_data *data);

/* Asynchronous Supervision (monitor.c) */
void	monitoring(t_data *data);

/* State Machine & Action Loggers (actions.c) */
void	philo_eat(t_philo *philo);
void	philo_sleep(t_philo *philo);
void	philo_think(t_philo *philo);
void	print_log(char *message, long action_time, t_philo *philo);

#endif
