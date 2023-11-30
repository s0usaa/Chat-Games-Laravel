<?php

namespace App\Http\Controllers;

use App\Models\Message;
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
            $party_id = $request->input('party_id');
            $userId = auth()->user()->id;
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
}
