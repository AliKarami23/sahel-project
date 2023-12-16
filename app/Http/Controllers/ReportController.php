<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{

    public function dashboard()
    {
        // تعداد کاربران Customer
        $Customers = User::role('Customer')->count();

        // مجموع قیمت سفارشات امروز
        $today = Carbon::today();
        $totalPriceToday = Order::whereDate('created_at', $today)
            ->where('payment_status', 1)
            ->sum('total_price');

        // لیست تمام محصولات و مجموع قیمت فروش هر محصول
        $productsWithSales = Product::with('reservation')->get()->map(function ($product) {
            return [
                'product' => $product,
                'totalSales' => $product->updateTicketsSold(),
            ];
        });

        // سفارشات ماه گذشته و مجموع قیمت آن‌ها
        $firstDayOfLastMonth = Carbon::now()->subMonth()->startOfDay();
        $lastDayOfLastMonth = Carbon::now()->endOfDay();

        $ordersLastMonth = Order::with('products')
            ->whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])
            ->get();

        $totalPriceLastMonth = $ordersLastMonth->sum('total_price');

        foreach ($ordersLastMonth as $order) {
            // اطلاعات سفارش
            $orderDetails = [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'total_price' => $order->total_price,
                'payment_status' => $order->payment_status,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ];

            $relatedProducts = $order->products;
        }

        $totalPriceLastMonth = $ordersLastMonth->sum('total_price');


        // تعداد بلیط‌های فروخته شده امروز
        $ticketsSoldTodayMan = Reservation::whereDate('created_at', $today)
            ->orWhereHas('sans', function ($query) use ($today) {
                $query->whereDate('date', $today);
            })
            ->sum('tickets_sold_man');

        $ticketsSoldTodayWoman = Reservation::whereDate('created_at', $today)
            ->orWhereHas('sans', function ($query) use ($today) {
                $query->whereDate('date', $today);
            })
            ->sum('tickets_sold_woman');

        $ticketsSoldToday = $ticketsSoldTodayMan + $ticketsSoldTodayWoman;

        return response()->json([
            'Customer' => $Customers,
            'totalPriceToday' => $totalPriceToday,
            'ticketsSoldToday' => $ticketsSoldToday,
            'LastMonth' => [
                'ordersLastMonth' => $ordersLastMonth,
                'totalPriceLastMonth' => $totalPriceLastMonth
            ],
            'productsWithSales' => $productsWithSales,
        ]);
    }
}
