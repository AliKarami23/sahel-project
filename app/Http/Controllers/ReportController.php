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
        $customerCount = User::role('Customer')->count();

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

        //محصولات و مجموع قیمت آن‌ها در ماه گذشته
        $firstDayOfLastMonth = now()->subMonth()->startOfDay();
        $lastDayOfLastMonth = now()->endOfDay();

        $productsLastMonth = Product::whereHas('orders', function ($query) use ($firstDayOfLastMonth, $lastDayOfLastMonth) {
            $query->whereBetween('orders.created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])
                ->where('orders.payment_status', 1);
        })
            ->with(['orders' => function ($query) use ($firstDayOfLastMonth, $lastDayOfLastMonth) {
                $query->whereBetween('orders.created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])
                    ->where('orders.payment_status', 1);
            }])
            ->get();

        $productsLastMonthSales = $productsLastMonth->map(function ($product) {
            return [
                'title' => $product->title,
                'price' => $product->price,
                'total_sales_last_month' => $product->getTotalSales(),
            ];
        });

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
            'customerCount' => $customerCount,
            'totalPriceToday' => $totalPriceToday,
            'ticketsSoldToday' => $ticketsSoldToday,
            'productsLastMonthSales' => $productsLastMonthSales,
            'productsWithSales' => $productsWithSales,
        ]);
    }
}
