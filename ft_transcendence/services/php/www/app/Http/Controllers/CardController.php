<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Http\Resources\CardResource;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::with('translations')->get();
    
        return response()->json([
            'success' => true,
            'data' => CardResource::collection($cards)
        ]);
    }
}
