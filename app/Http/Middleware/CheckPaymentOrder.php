<?php

namespace App\Http\Middleware;

use App\Models\Order;
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
        $order = Order::findOrFail($request->id);

        if ($order->payment_status == 1) {
            return response()->json(['message' => 'Order payment has already been completed. Cannot edit.'], 403);
        }

        return $next($request);
    }
}
