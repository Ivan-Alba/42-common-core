<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardResource;
use Illuminate\Http\Request;

class CardUserController extends Controller
{
    public function getMyCards(Request $request)
    {
        $cards = $request->user()->cards()->get();

        return CardResource::collection($cards);
    }
}
