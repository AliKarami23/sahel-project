<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
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

        $payment = Payment::create([
            'user_id' =>$user->id ,
            'status' => 'pending',
            'track_id' => rand(100000, 999999),
            'order_id' => $order->id,
            'amount' => $order->Total_Price,
            'card_no' => 1234567890123456,
            'hashed_card_no' => md5('1234567890123456'),
            'date' => now(),
        ]);

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
                'Payment_Status' => true
            ]);

            $payment->update(['status' => 'successful']);

            $pdfUrl = $cardController->CreateCard($order, $uniqueCardNumber);

            return response()->json(['message' => 'Payment was successful', 'pdf_url' => $pdfUrl], 200);
        } else {

            $payment->update(['status' => 'failed']);

            return response()->json(['error' => 'Payment request failed'], 400);
        }
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


    public function PaymentList()
    {
        $PaymentList = Payment::all();
        return response()->json([
            'PaymentList' => $PaymentList
        ]);
    }

    public function PaymentFilter(Request $request)
    {
        $filterType = $request->filter_type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $paymentsQuery = Payment::query();

        switch ($filterType) {
            case 'daily':
                $paymentsQuery->whereDate('date', Carbon::today()->toDateString());
                break;

            case 'weekly':
                $paymentsQuery->whereBetween('date', [Carbon::now()->subWeek(), Carbon::now()]);
                break;

            case 'monthly':
                $paymentsQuery->whereMonth('date', Carbon::now()->subMonth()->month);
                break;

            case 'custom':
                $paymentsQuery->whereBetween('date', [Carbon::parse($startDate), Carbon::parse($endDate)]);
                break;

            default:
                break;
        }

        $filteredPayments = $paymentsQuery->get();

        return response()->json([
            'PaymentList' => $filteredPayments
        ]);
    }

}
