<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function profile(){
        $user  = auth()->user();

        return response([
            "success" => true,
            "message" => "Perfil de usuario aceptado",
            "data" => $user
        ],
        Response::HTTP_OK
    );
    }

    public function profileUpdate(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'name' => 'required|string',
                'surname' => 'required|string',
                'nickname' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|min:6|max:12',
                'age' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(),400);
            }

            $user = User::create([
                'name' => $request['name'],
                'surname' => $request['surname'],
                'nickname' => $request['nickname'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'age' => $request['age'],
                'role_id' => 2,
            ]);
            $token = $user->createToken('apiToken')->plainTextToken;
            $res = [
                "success" => true,
                "message" => "Perfil actualizado correctamente",
                "data" => $user,
                "token" => $token
            ];
            return response()->json(
                $res,
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            Log::error("Error al actualizar el perfil: " . $th->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Error en el registro"
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
        }
    }
}
