<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{

    public function Dashboard()
    {
        // تعداد کاربران Customer
        $Customers = User::role('Customer')->count();

        // مجموع قیمت سفارشات امروز
        $today = Carbon::today();
        $totalPriceToday = Order::whereDate('created_at', $today)->sum('Total_Price');

        // لیست تمام محصولات و مجموع قیمت فروش هر محصول
        $productsWithSales = Product::all()->map(function ($product) {
            return [
                'product' => $product,
                'totalSales' =>     $product->updateTicketsSold(),
            ];
        });

        // سفارشات ماه گذشته و مجموع قیمت آن‌ها
        $firstDayOfLastMonth = Carbon::now()->subMonth()->firstOfMonth();
        $lastDayOfLastMonth = Carbon::now()->subMonth()->lastOfMonth();

        $ordersLastMonth = Order::whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])
            ->get();


        $totalPriceLastMonth = $ordersLastMonth->sum('Total_Price');

        // تعداد بلیط‌های فروخته شده امروز
        $ticketsSoldTodayMan = Reservation::whereDate('created_at', $today)
            ->orWhereHas('sans', function ($query) use ($today) {
                $query->whereDate('Date', $today);
            })
            ->sum('Tickets_Sold_Man');

        $ticketsSoldTodayWoman = Reservation::whereDate('created_at', $today)
            ->orWhereHas('sans', function ($query) use ($today) {
                $query->whereDate('Date', $today);
            })
            ->sum('Tickets_Sold_Woman');

        $ticketsSoldToday = $ticketsSoldTodayMan + $ticketsSoldTodayWoman;

        return response()->json([
            'CountCustomer' => $Customers,
            'totalPriceToday' => $totalPriceToday,
            'ticketsSoldToday' => $ticketsSoldToday,
            'LastMonth' => [
                'ordersLastMonth' => $ordersLastMonth,
                'totalPriceLastMonth' => $totalPriceLastMonth
            ],
            'productsWithSales' => $productsWithSales,
        ]);
    }

    public function FinancialReport(){

    }
}
