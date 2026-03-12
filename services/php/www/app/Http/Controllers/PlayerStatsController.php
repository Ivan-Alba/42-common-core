<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\PlayerStatResource;
use Illuminate\Http\Request;

class PlayerStatsController extends Controller
{
    /**
     * Devuelve las estadísticas de un usuario específico por su ID.
     */
    public function getUserStats(User $user)
    {
        // Cargamos la relación stats del usuario recibido
        // Si el usuario no tiene stats aún, esto devolverá null o vacío
        $stats = $user->stats;

        if (!$stats) {
            return response()->json(['message' => 'Estadísticas no encontradas'], 404);
        }

        return new PlayerStatResource($stats);
    }
}
