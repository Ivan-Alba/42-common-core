/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   raycast_manager.c                                  :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: igarcia2 <igarcia2@student.42barcel>       +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2025/01/20 15:27:13 by igarcia2          #+#    #+#             */
/*   Updated: 2025/02/18 21:01:45 by igarcia2         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../../inc/cub3d.h"
#include "../../inc/tile.h"

void	corrected_wall_distance(t_raycast *ray_data, t_data *data)
{
	double	beta;

	beta = ray_data->alpha - data->player->angle;
	beta = normalize_angle(beta);
	if (beta > 180)
		beta = (360 - beta);
	data->ray_data->corrected_distance
		= data->ray_data->shortest_distance * cos(deg_to_rad(beta));
	ray_data->wall_height = ceil((TILE_SIZE * (ray_data->distance_pp))
			/ ray_data->corrected_distance);
	if (ray_data->wall_height < HEIGHT - (HEIGHT * UI_SIZE))
		ray_data->wall_y = (HEIGHT + (HEIGHT * UI_SIZE)) / 2
			- ray_data->wall_height / 2 - 1;
	else
		ray_data->wall_y = HEIGHT * UI_SIZE;
}

void	fix_corner_case_intersection(double distance[2], t_raycast *ray_data,
		t_data *data)
{
	int	horz[2];
	int	vert[2];
	int	wall[2];

	horz[X] = (int)round((ray_data->horz_hit[X]) / TILE_SIZE);
	horz[Y] = (int)round((ray_data->horz_hit[Y]) / TILE_SIZE);
	vert[X] = (int)round((ray_data->vert_hit[X]) / TILE_SIZE);
	vert[Y] = (int)round((ray_data->vert_hit[Y]) / TILE_SIZE);
	if (ray_data->alpha > 90 && ray_data->alpha < 270)
		horz[X] -= 1;
	else
		vert[X] -= 1;
	if (ray_data->alpha > 0 && ray_data->alpha < 180)
		vert[Y] -= 1;
	else
		horz[Y] -= 1;
	wall[HORZ] = get_tile_type(horz, data->map_data);
	wall[VERT] = get_tile_type(vert, data->map_data);
	ray_data->shortest_distance = distance[HORZ];
	if (wall[VERT] == TILE_WALL)
		distance[VERT] = 0;
	else
		distance[HORZ] = 0;
}

void	get_shortest_dist(t_player *player, t_raycast *ray_data, t_data *data)
{
	double	distance[2];

	distance[HORZ] = 0;
	distance[VERT] = 0;
	if (ray_data->horz_hit[X] > 0)
		distance[HORZ] = sqrt(pow(player->pos[X] - ray_data->horz_hit[X], 2)
				+ pow(player->pos[Y] - ray_data->horz_hit[Y], 2));
	if (ray_data->vert_hit[X] > 0)
		distance[VERT] = sqrt(pow(player->pos[X] - ray_data->vert_hit[X], 2)
				+ pow(player->pos[Y] - ray_data->vert_hit[Y], 2));
	if (ray_data->vert_hit[X] <= 0)
		ray_data->shortest_distance = distance[HORZ];
	else if (ray_data->horz_hit[X] <= 0)
		ray_data->shortest_distance = distance[VERT];
	else if (distance[HORZ] < distance[VERT])
		ray_data->shortest_distance = distance[HORZ];
	else if (distance[VERT] < distance[HORZ])
		ray_data->shortest_distance = distance[VERT];
	if (fabs(distance[HORZ] - distance[VERT]) < EPSILON)
		fix_corner_case_intersection(distance, ray_data, data);
	ray_data->vtx_hit = Y;
	if (ray_data->shortest_distance == distance[HORZ])
		ray_data->vtx_hit = X;
}

void	render_column(int x, t_raycast *ray_data, t_data *data)
{
	int	y;

	y = (HEIGHT * UI_SIZE);
	while (y < HEIGHT)
	{
		if (y >= ray_data->wall_y
			&& y <= ray_data->wall_y + ray_data->wall_height)
		{
			if (is_door_hit(ray_data, data))
				render_door(x, &y, ray_data, data);
			else
				render_wall(x, &y, ray_data, data);
		}
		else if (y > ray_data->wall_y + ray_data->wall_height)
			render_ceil_floor(x, &y, data);
		else
			y++;
	}
}

void	raycast_manager(t_raycast *ray_data, t_data *data)
{
	int	x;

	x = 0;
	ray_data->alpha = data->player->angle + FOV / 2;
	while (x < WIDTH)
	{
		ray_data->alpha = normalize_angle(ray_data->alpha);
		horz_hit(ray_data->alpha, data->player, data);
		vert_hit(ray_data->alpha, data->player, data);
		get_shortest_dist(data->player, data->ray_data, data);
		corrected_wall_distance(ray_data, data);
		check_sprites_along_ray(ray_data, data);
		render_column(x, ray_data, data);
		reset_sprite_visibility(data->map_data);
		ray_data->alpha -= ray_data->angle_increment;
		ray_data->shortest_distance = 0;
		x++;
	}
}
