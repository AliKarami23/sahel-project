<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPaymentStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $orderId = $request->route('id');

        $order = Order::find($orderId);

        if ($order && $order->Payment_Status) {
            return response()->json(['error' => 'It is not possible to edit an order with confirmed payment status.'], 403);
        }
        return $next($request);
    }
}
