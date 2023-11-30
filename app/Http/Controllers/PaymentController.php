<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Evryn\LaravelToman\Facades\Toman;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function Payment(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $user = Auth::user();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $request = Toman::orderId($order->id)
            ->amount(50000)
            ->description('Payment for buying entertainment on the Sahel website')
            ->callback(route('PaymentCallBack'))
            ->mobile($user->Phone_Number)
            ->email($user->Email)
            ->name($user->Full_Name)
            ->request();

        if ($request->successful()) {
            $transactionId = $request->transactionId();
            return $request->pay();
        }

        if ($request->failed()) {
            return response()->json(['error' => 'Payment request failed'], 400);
        }


    }

    public function callback(Request $request)
    {
//        $order = Order::findOrFail($request->order_id);
//        if (!$order) {
//            return response()->json(['message' => 'Order not found'], 404);
//        }
        $payment = $request->amount(50000)->verify();

        if ($payment->successful()) {
            $referenceId = $payment->referenceId();
            return response()->json(['status' => 'ok']);
        }

        if ($payment->failed()) {
            return response()->json(['error' => 'Payment verification failed'], 400);
        }
    }

}
