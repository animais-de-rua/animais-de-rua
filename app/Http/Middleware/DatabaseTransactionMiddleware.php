<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class DatabaseTransactionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): BaseResponse
    {
        DB::beginTransaction();

        /** @var Response $response */
        $response = $next($request);

        ! is_null($response->exception)
            ? DB::rollBack()
            : DB::commit();

        return $response;
    }
}
