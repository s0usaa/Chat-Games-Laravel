<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Party;
use App\Models\User;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                "message" => "Detalles de la party",
                "data" => [
                    "id" => $party->id,
                    "name" => $party->name,
                    "rules" => $party->rules,
                    "Title of Game" => $gameTitle,
                    "game_id" => $party->game_id,
                    ]
                ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

    public function joinParty(Request $request){
        try {
            $party_id = $request->input("party_id");
            $myId = auth()->user()->id;
            $user = User::find($myId);
            $party = Party::find($party_id);
            $party_userID = $party->users()->find($myId);
            if($party_userID && $user){
                return response()->json([
                    "success" => true,
                    "message" => "Ya estas en una party"
                ]);
            }else{
                $partyJoin = DB::table("party_user")->insert([
                    "party_id" => $party_id,
                    "user_id" => $myId,
                ]);
            }
            
            return response()->json([
                "success" => true,
                "message" => "Has entrado en la party correctamente",
                "data" => $partyJoin,
            ],200);

        } catch (\Throwable $th) {
            Log::error("Error al entrar en una party " . $th->getMessage());
            return response()->json([
                "success" => false,
                "message" => "No has podido unirte a la party"
            ],500);
        }
    }

    public function leaveParty(Request $request, $id){
        try {
            Log::info("Salir de la party funciona");
            $myId = auth()->user()->id;
            $user = User::find($myId);
            $userID = $user->id;
            $partyLeave = DB::table("party_user")->where("id", "=", $id)->find($id);
            $party_userID = $partyLeave->user_id;
            if($party_userID == $userID){
                $partyDelete = DB::table("party_user")->where("id", "=", $id)->delete($id);
                $partyDelete;
                return response()->json([
                    "success" => true,
                    "message" => "Has salido de la party correctamente",
                ]);
            }else{
                return response()->json([
                    "success" => true,
                    "message" => "No estas en la party"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage(),
            ],500);
        }
    }
}
