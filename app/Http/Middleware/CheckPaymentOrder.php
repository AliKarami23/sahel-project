<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPaymentOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $order = Order::findOrFail(route('id'));

        if ($order->payment_status == true) {
            return response()->json(['message' => 'Order payment has already been completed. Cannot edit.'], 403);
        }

        return $next($request);
    }
}
