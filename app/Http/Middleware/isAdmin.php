<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info("Middleware de Admin");
        $userId = auth()->user()->id;
        $user = User::find($userId);
        $role = $user->role_id;
        if($role != 1) {
            return response()->json([
                "success" => true,
                "message" => "No tienes poder aqui"
            ]);
        }
        return $next($request);
    }
}
