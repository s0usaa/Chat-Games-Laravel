<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request){

        try{
            Log::info("Register User Working");
            $validator = Validator::make($request->all(),[
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|min:6|max:14',
            ]);
            
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'role_id' => 2,
            ]);

            $token = $user->createToken('apiToken')->plainTextToken;
            
            $res = [
                "succes" => true,
                "message" => "Usuario registrado correctamente",
                "data" => $user,
                "token" => $token
            ];
            
            return response()->json(
                $res,
                Response::HTTP_CREATED
            );
        
        }catch(\Throwable $th){
            Log::error("Register error: " . $th->getMessage());
            return response()->json([
                "succes" => false,
                "message" => "Error en el registro"
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
        }
    }

    public function login(Request $request){
        try {
            Log::info("Login User Working");
            $validator = Validator::make($request->all(),[
                'email' => 'required|string',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(),400);
            }

            $user = User::query()->where('email', $request['email'])->first();
            //Verificamos si el email existe
            if (!$user) {
                return response([
                    "success" => false,
                    "message" => "El email no es valido o no existe",
                ],
            Response::HTTP_NOT_FOUND
        );
            }
            //Verificamos la contraseña
            if (!Hash::check($request['password'], $user->password)) {
                return response([
                    "success" => false,
                    "message" => "El password no es valido"
                ],
                Response::HTTP_NOT_FOUND
            );
                }

                $token = $user->createToken('apiToken')->plainTextToken;

                $res = [
                    "success" => true,
                    "message" => "Sesion iniciada correctamente",
                    "token" => $token
                ];

                return response()->json(
                    $res,
                    Response::HTTP_ACCEPTED
                );
        } catch (\Throwable $th) {
            Log::error("Error en el Login: " . $th->getMessage());

            return response()->json([
                "success" => false,
                "message" => "Error en el login"
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
        }
    }

    public function logout(Request $request){
        try {
            $accessToken = $request->bearerToken();
            //Cogemos el token de la base de datos
            $token = PersonalAccessToken::findToken($accessToken);
            //Eliminar el token
            $token->delete();

            return response([
                "success" => true,
                "message" => "Sesion cerrada correctamente"
            ],
            Response::HTTP_OK
        );
        } catch (\Throwable $th) {
            Log::error("Error al cerrar sesion: " . $th->getMessage());

            return response()->json([
                "success" => false,
                "message" => "Error en el perfil"
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
        }
    }

}
