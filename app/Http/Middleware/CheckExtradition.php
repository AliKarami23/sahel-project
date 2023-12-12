<?php

namespace App\Http\Middleware;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExtradition
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $orderId = $request->order_id;
        $order = Order::with('products')->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }
        if ($order->payment_status == 0) {
            return response()->json(['error' => 'The order fee has not been paid.'], 404);
        }

        $productIds = $order->products->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get();

        $hasExtradition = false;

        foreach ($products as $product) {
            if ($product->extradition == 'yes') {
                $sans = $product->sans;

                if ($sans) {
                    $startDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $sans->date . ' ' . $sans->start);
                    $remainingTime = $startDateTime->diffInMinutes(Carbon::now());

                    if ($remainingTime > 0) {
                        $hasExtradition = true;
                    } else {
                        return response()->json(['error' => 'Extradition time has ended.'], 403);
                    }
                } else {
                    return response()->json(['error' => 'No Sans found for the product.'], 403);
                }
            }
        }
        if ($hasExtradition) {
            return $next($request);
        } else {
            return response()->json(['error' => 'No products with extradition found.'], 403);
        }
    }
}
