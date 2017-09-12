<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response as HttpResponse;
use App\Client;
use Illuminate\Support\Facades\Hash;
class clientAuthentication
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
        $client_id = $request->header('clientId');
        
        $request_hash = $request->header('hash');
        $client = Client::where(['client_id'=>$client_id])->first();
        if (!$client) {
            return response()->json(['error' => 'App doesn\'t have permission to access the Api'], HttpResponse::HTTP_UNAUTHORIZED);
        }
        $string = $client_id.','.$client->client_key;
        if (!Hash::check($string,$request_hash)) {
            return response()->json(['error' => 'App doesn\'t have permission to access the Api'], HttpResponse::HTTP_UNAUTHORIZED);
        }
         $request->attributes->add(['client'=>$client]);
        return $next($request);
    }
}
