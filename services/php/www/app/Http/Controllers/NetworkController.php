<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Events\PongEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NetworkController extends Controller
{
    /**
     * Receives a ping from the client and broadcasts a PongEvent 
     * with the server's current time.
     */
    public function sendPong(Request $request): JsonResponse
    {
        $request->validate([
            'client_timestamp' => 'required|numeric',
        ]);

        $user = $request->user();
        $serverTime = microtime(true);

        // Broadcast the pong event immediately (ShouldBroadcastNow)
        broadcast(new PongEvent(
            $user->id,
            (float) $request->client_timestamp,
            $serverTime
        ));

        return response()->json([
            'success' => true,
            'message' => 'Pong broadcasted'
        ]);
    }
}
