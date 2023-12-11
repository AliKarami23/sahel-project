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
        $totalPriceToday = Order::whereDate('created_at', $today)->sum('total_price');

        // لیست تمام محصولات و مجموع قیمت فروش هر محصول
        $productsWithSales = Product::all()->map(function ($product) {
            return [
                'product' => $product,
                'totalSales' => $product->updateTicketsSold(),
            ];
        });

        // سفارشات ماه گذشته و مجموع قیمت آن‌ها
        $firstDayOfLastMonth = Carbon::now()->subMonth()->firstOfMonth();
        $lastDayOfLastMonth = Carbon::now()->subMonth()->lastOfMonth();

        $ordersLastMonth = Order::whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])
            ->get();


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
