<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOrderPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $order = Order::find($request->id);

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // Check if the user is an admin or the owner of the order
        if (!$user->hasRole('Admin') && $user->id !== $order->user_id) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        // Check if the order is already paid
        if ($order->Payment_Status) {
            return response()->json(['message' => 'Order payment has already been completed. Cannot edit.'], 403);
        }

        return $next($request);    }
}
