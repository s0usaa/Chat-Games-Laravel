<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Message;
use App\Models\Party;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\isNull;
use function PHPUnit\Framework\returnValue;

class UserController extends Controller
{
    public function profile(){
       try {
        $user  = auth()->user();
        return response([
            "success" => true,
            "message" => "Perfil de usuario aceptado",
            "data" => $user
        ],
        Response::HTTP_OK
    );
       } catch (\Throwable $th) {
        Log::error("Error al ver tu perfil: " . $th->getMessage());
        return response()->json([
            "success" => false,
            "message" => "No puedes acceder a tu perfil"
        ],
        Response::HTTP_INTERNAL_SERVER_ERROR
    );
       } 
    }

    public function profileUpdate(Request $request){
        try {
            Log::info("Update Profile Working");
            $id = auth()->user()->id;
            $id2 = DB::table('users')->where('id', '=', $id)->get();

            // $validator = Validator::make($request->all(),[
            //     'name' => 'string|regex:/^[A-Za-z0-9]+$/|max:20',
            //     'surname' => 'required|string|regex:/^[A-Za-z0-9]+$/|max:20',
            //     'nickname' => 'string|unique:users,nickname|regex:/^[A-Za-z0-9]+$/|max:20',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json($validator->errors(),400);
            // }

            $user = User::find($id);

            if (!$id2) {
                return response()->json([
                    "success" => true,
                    "message" => "El usuario no existe"
                ],404);
            }

            $name = $request->input('name');
            $surname = $request->input('surname');
            $nickname = $request->input('nickname');
            $age = $request->input('age');

            if(isNull($name,$surname,$nickname,$age)){
                $user->name = $name;
                $user->surname = $surname;
                $user->nickname = $nickname;
                $user->age = $age;
            }

            $user->save();

            return response()->json([
                "success" => true,
                "message" => "Perfil actualizado correctamente",
                "data" => $user
            ],200);

        } catch (\Throwable $th) {
            Log::error("Error al actualizar el perfil: " . $th->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Error al actualizar el perfil"
            ],
            500
        );
        }
    }

    public function createComment(Request $request){
        try {

            $validator = Validator::make($request->all(),[
                "comments" => "string",
                "party_id" => "integer",
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $userId = auth()->user()->id;
            $party_id = $request->input('party_id');
            $comments = $request->input('comments');

            $newMessage = new Message();
            $newMessage->party_id = $party_id;
            $newMessage->user_id = $userId;
            $newMessage->comments = $comments;
            $newMessage->save();

            return response()->json([
                "success" => true,
                "message" => "Mensaje creado",
                "data" => $newMessage
            ],200);
        } catch (\Throwable $th) {
            Log::error("Error al crear el mensaje: " . $th->getMessage());

            return response()->json([
                "succes" => false,
                "message" => "Error al crear el mensaje"
            ],500);
        }
    }

    public function viewMyMessages()
    {
        try {
            $id = auth()->user()->id;
            $message = DB::table('messages')->where('user_id', '=', $id)->get();

            return response()->json([
                "success" => true,
                "message" => "Estos son sus mensajes" . $message,
                "data" => $message
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage() . $message
            ],500);
        }
    }

    public function viewAllUsers(){
        try {
            $users = User::query()->get();
            
            return [
                "success" => true,
                "data" => $users
            ];
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage() . $users
            ],500);
        }
    }

    public function deleteUserById(Request $request, $id){
        try {
            $user = User::find($id);
            if($user->role_id != 1){
                User::destroy($id);
                return response()->json([
                    "success" => true,
                    "message" => "Usuario eliminado correctamente"
                ],200);
            }else{
                return response()->json([
                    "success" => false,
                    "message" => "El perfil de admin no se puede borrar"
                ],400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ],500);
        }
    }

    public function usersDetailsById(Request $request, $id){
        try {
            $users = User::query()->find($id);
            return response()->json([
                "success" => true,
                "message" => "Detalles de Usuario",
                "data" => [
                    "id" => $users->id,
                    "name" => $users->name,
                    "surname" => $users->surname,
                    "nickname" => $users->nickname,
                    "age" => $users->age,
                    "email" => $users->email
                ]
                ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ],500);
        }
    }

    public function updateMessagesByIdAdmin(Request $request, $id){
        try {
            $validator = Validator::make($request->all(),[
                "comments" => "string",
                "party_id" => "integer",
            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $comments = $request->input("comments");
            $party_id = $request->input("party_id");
            $message = Message::find($id);

            if(isNull($comments, $party_id)){
                $message->comments = $comments;
                $message->party_id = $party_id;
            }

            $message->save();
            return response()->json([
                "success" => true,
                "message" => "Mensaje actualiado correctamente",
                "data" => $message
            ],200);
        } catch (\Throwable $th) {
            Log::error("Update Profile error " . $th->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Error al actualizar el mensaje " . ($message)
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function viewMessagesPartyById($id){
        try {
            $message = DB::table('messages')->where("party_id", "=", $id)->get();
            $party = Party::query()->find($id);
            $gameId = $party->game_id;
            $partyName = $party->name;
            $gameId = $party->game_id;
            $gameData = Game::query()->find($gameId);
            $gameTitle = $gameData->title;

            return response()->json([
                "success" => true,
                "message" => "Mensajes del Id del Juego",
                "data" => [
                    "Titulo del Juego" => $gameTitle,
                    "Mensaje" => $message
                ]
                ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage() . $message
            ], 500);
        }
    }

    public function deleteCommentByIdAdmin(Request $request, $id){
        try {
            Message::destroy($id);
            
            return response()->json([
                "success" => true,
                "message" => "Mensaje borrado correctamente"
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage(),
            ],500);
        }
    }

    public function deleteCommentByIdUser(Request $request, $id){
        try {
            $myId = auth()->user()->id;
            $user = User::find($myId);
            $party = Message::find($id);
            if($user->id == $party->user_id){
                Message::where('id', $id)->delete();
                return response()->json([
                    "success" => true,
                    "message" => "Mensaje borrado correctamente",
                ],200);
            }else{
                return response()->json([
                    "success" => true,
                    "message" => "No puedes borrar mensajes de otros usuarios " . $user,
                ],400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage() . $party
            ], 500);
        }
    }

    public function updateMessagesByIdUser(Request $request, $id){
        try {
            $validator = Validator::make($request->all(),[
                "comments" => "string",
                "party_id" => "integer"
            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $comments = $request->input("comments");
            $party_id = $request->input("party_id");
            $message = Message::find($id);
            $myId = auth()->user()->id;
            $user = User::find($myId);
            if($user->id == $message->user_id){
                $message->comments = $comments;
                $message->party_id = $party_id;
                $message->save();
                return response()->json([
                    "success" => true,
                    "message" => "Mensaje actualizado correctamente",
                    "data" => $message,
                ],200);
            }else{
                return response()->json([
                    "success" => true,
                    "message" => "No puedes actualizar el mensaje de otros usuarios"
                ]);
            }
        } catch (\Throwable $th) {
            Log::error("Delete Message By Id User error: " . $th->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Error al borrar el mensaje " . ($message)
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
