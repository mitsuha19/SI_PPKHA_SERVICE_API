<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Ambil token dari header Authorization
        $token = $request->bearerToken();
        if (! $token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            // 2. Decode & verifikasi signature + expiration
            $decoded = JWT::decode(
                $token,
                new Key(env('JWT_SECRET'), 'HS256')
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid or expired token',
                'message' => $e->getMessage()
            ], 401);
        }

        // 3. Simpan klaim ke request agar controller bisa akses
        $request->attributes->set('jwt_claims', (array)$decoded);

        return $next($request);
    }
}
