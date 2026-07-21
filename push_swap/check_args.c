/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   check_args.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/02/11 16:05:26 by igarcia2          #+#    #+#             */
/*   Updated: 2024/02/23 19:20:38 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "push_swap.h"

/*
** @brief  Frees allocated memory, prints the standard error message, and exits.
** @param  array: Pointer to the allocated integer array to be freed.
*/
void	args_error(int **array)
{
	if (array && *array)
	{
		free(*array);
		*array = NULL;
	}
	write(2, ERROR_MSG, sizeof(ERROR_MSG));
	exit(0);
}

/*
** @brief  Validates number formatting, checks integer overflow, and converts
**         to int.
** @param  arg: String representation of the input argument.
** @param  array: Pointer to the integer array storing parsed numbers.
** @param  pos: Index position within the destination array.
*/
void	check_int(char *arg, int **array, int pos)
{
	long	num;
	int		sign;
	int		i;

	num = 0;
	sign = 1;
	i = 0;
	if (arg[i] == '-' || arg[i] == '+')
	{
		if (arg[i++] == '-')
			sign = -1;
	}
	if (arg[i] == '\0')
		args_error(array);
	while (arg[i] != '\0')
	{
		if (arg[i] < '0' || arg[i] > '9')
			args_error(array);
		num = num * 10 + (arg[i] - '0');
		i++;
		if (num * sign > INT_MAX || num * sign < INT_MIN)
			args_error(array);
	}
	num *= sign;
	(*array)[pos] = (int)num;
}

/*
** @brief  Checks for duplicate values within the integer array.
** @param  array: Pointer to the array containing parsed integers.
** @param  len: Total number of elements stored in the array.
*/
void	check_duplicate(int **array, int len)
{
	int	i;
	int	j;

	i = 0;
	j = 0;
	while (i < len)
	{
		j = i + 1;
		while (j < len)
		{
			if ((*array)[i] == (*array)[j])
				args_error(array);
			j++;
		}
		i++;
	}
}

/*
** @brief  Verifies if array is already sorted; if true, frees memory and
**         exits.
** @param  array: Pointer to the array containing parsed integers.
** @param  count: Total number of integer elements.
*/
void	is_ordered(int **array, int count)
{
	int	i;

	i = 0;
	while (i < count - 1)
	{
		if ((*array)[i] > (*array)[i + 1])
			return ;
		i++;
	}
	free(*array);
	exit(0);
}

/*
** @brief  Validates command-line arguments, converts them, and checks
**         duplicates.
** @param  argc: Total number of input arguments.
** @param  argv: Array of string arguments.
** @param  count: Pointer storing the total count of valid numbers parsed.
** @return Pointer to the populated array of unique, unsorted integers.
*/
int	*check_args(int argc, char *argv[], int *count)
{
	int	i;
	int	*array;

	array = NULL;
	if (argc > 1)
	{
		i = 1;
		array = (int *)malloc((argc - 1) * sizeof(int));
		while (i < argc)
		{
			check_int(argv[i], &array, i - 1);
			i++;
		}
		check_duplicate(&array, argc - 1);
		*count = argc - 1;
		is_ordered(&array, *count);
	}
	return (array);
}
