<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Party;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartyController extends Controller
{
    public function createParty(Request $request){
        try {
            $game_id = $request->input("game_id");
            $name = $request->input("name");
            $rules = $request->input("rules");

            $newParty = new Party();
            $newParty->game_id = $game_id;
            $newParty->name = $name;
            $newParty->rules = $rules;
            $newParty->save();

            return response()->json([
                "success" => true,
                "message" => "Party creada correctamente",
                "data" => $newParty,
            ],200);

        } catch (\Throwable $th) {
            Log::error("Creacion de una party error: " . $th->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Error al crear una party" . $newParty
            ],500);
        }
    }

    public function getPartyById(Request $request, $id){
        try {
            $party = Party::query()->find($id);
            $gameId = $party->game_id;
            $gameData = Game::query()->find($gameId);
            $gameTitle = $gameData->title;
            return response()->json([
                "success" => true,
                "message" => "Detalle de la Party",
                "data" => [
                    "id" => $party->id,
                    "name" => $party->name,
                    "rules" => $party->rules,
                    "Title of Game" => $gameTitle,
                    "game_id" => $party->game_id
                ]
                ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }
}
