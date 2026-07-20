/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_split.c                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2024/01/11 17:05:32 by igarcia2          #+#    #+#             */
/*   Updated: 2024/01/17 19:03:38 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

/*
** @brief  Counts the number of words delimited by 'c' in the string 's'.
*/
static int	count_words(char const *s, char c)
{
	int	count;
	int	i;

	count = 0;
	i = 0;
	while (s[i] != '\0')
	{
		while (s[i] == c)
			i++;
		if (s[i] != '\0')
		{
			count++;
			while (s[i] != '\0' && s[i] != c)
				i++;
		}
	}
	return (count);
}

/*
** @brief  Allocates memory and copies a word of length 'len' from string 's'.
*/
static char	*get_word(const char *s, int len)
{
	char	*res;
	int		j;

	res = (char *)malloc((len + 1) * sizeof(char));
	if (!res)
		return (NULL);
	j = 0;
	while (j < len)
	{
		res[j] = s[j];
		j++;
	}
	res[j] = '\0';
	return (res);
}

/*
** @brief  Locates the word at index 'word_idx' and returns its copy.
*/
static char	*read_word(const char *s, char c, int word_idx)
{
	int	i;
	int	w_count;
	int	start;

	i = 0;
	w_count = 0;
	while (s[i] != '\0')
	{
		while (s[i] == c)
			i++;
		if (s[i] != '\0')
		{
			if (w_count == word_idx)
			{
				start = i;
				while (s[i] != '\0' && s[i] != c)
					i++;
				return (get_word(&s[start], i - start));
			}
			w_count++;
			while (s[i] != '\0' && s[i] != c)
				i++;
		}
	}
	return (NULL);
}

/*
** @brief  Splits 's' into an array of strings using 'c' as a delimiter.
** @param  s: The string to be split.
** @param  c: The delimiter character.
** @return The array of new strings, or NULL if allocation fails.
*/
char	**ft_split(char const *s, char c)
{
	char	**result;
	int		i;
	int		words;

	if (!s)
		return (NULL);
	words = count_words(s, c);
	result = (char **)malloc((words + 1) * sizeof(char *));
	if (!result)
		return (NULL);
	i = 0;
	while (i < words)
	{
		result[i] = read_word(s, c, i);
		if (!result[i])
		{
			while (i > 0)
				free(result[--i]);
			free(result);
			return (NULL);
		}
		i++;
	}
	result[i] = NULL;
	return (result);
}
