<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use Firebase\JWT\SignatureInvalidException;

class JwtMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');


        // Validate presence and format of Authorization header
        if (empty($authorizationHeader) || !preg_match('/^Bearer\s+(.*?)$/', $authorizationHeader, $matches)) {
            return Response::json([
                'status' => 0,
                'message' => 'Invalid authorization header format.'
            ], 401);
        }

        $jwt = $matches[1];

        try {
            $secretKey = env('JWT_SECRET');
         
            $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));        
            // Attach decoded data and JWT to the request for access in controllers
            $request->attributes->add([
                'decoded' => $decoded,
                'jwt' => $jwt,
            ]);

            return $next($request);
        } catch (SignatureInvalidException $e) {
            return Response::json([
                'status' => 0,
                'message' => 'Invalid token.'
            ], 401);
        }catch (JWT\ExpiredException $e) {
            return Response::json([
                'status' => 0,
                'message' => 'Token has expired.'
            ], 401);
        } catch (JWT\Exception $e) {
            return Response::json([
                'status' => 0,
                'message' => 'Invalid token.'
            ], 401);
        }
    
    }

 
}
