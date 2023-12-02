<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Order;
use Evryn\LaravelToman\Facades\Toman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CardController;

class PaymentController extends Controller
{
    public function Payment(Request $request)
    {
        $order = Order::find($request->order_id);
        $user = Auth::user();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $request = Toman::orderId($order->id)
            ->amount($order->Total_Price)
            ->description('Payment for buying entertainment on the Sahel website')
            ->callback(route('callback'))
            ->mobile($user->Phone_Number)
            ->email($user->Email)
            ->name($user->Full_Name)
            ->request();

        if ($request->successful()) {
            $cardController = new CardController();
            $uniqueCardNumber = $cardController->generateUniqueCardNumber();

            $card = Card::updateOrCreate(
                ['order_id' => $order->id],
                ['Card_Number' => $uniqueCardNumber, 'Card_Link' => route('download_pdf', ['order_id' => $order->id])]
            );

            $order->update([
                'Patent_Status' => true
            ]);

            $pdfUrl = $cardController->CreateCard($order, $uniqueCardNumber);

            return response()->json(['message' => 'Payment was successful'], 400);
//            $transactionId = $request->transactionId();
//            return $request->pay();
        }

//        if ($request->failed()) {
//            return response()->json(['error' => 'Payment request failed'], 400);
//        }


    }

    public function callback(Request $request)
    {
//        $payment = $request->amount(50000)->verify();
//
//        if ($payment->successful()) {
//            $referenceId = $payment->referenceId();
//            return response()->json(['status' => 'ok']);
//        }
//
//        if ($payment->failed()) {
//            return response()->json(['error' => 'Payment verification failed'], 400);
//        }
    }
}
