<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Http\Resources\CardResource;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::all();

        return CardResource::collection($cards);
    }
}
