<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomerAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        $customerId = $request->route('id');

        if ($user->Role === 'admin' || $user->id == $customerId) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
