<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Card;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Evryn\LaravelToman\CallbackRequest;
use Evryn\LaravelToman\Facades\Toman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function payment(PaymentRequest $request)
    {
        $order = Order::find($request->order_id);
        $user = Auth::user();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $paymentRequest = Toman::amount($order->total_price)
            ->description('پرداخت هزینه خرید تفریح در سایت ساحل')
            ->callback(route('callbackPayment'))
            ->mobile($user->phone_number)
            ->email($user->email)
            ->request();

        if ($paymentRequest->successful()) {

            $payment = Payment::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'gateway_result' => json_encode(['transactionId' => $paymentRequest->transactionId()]),
                'price' => $order->total_price,
                'status' => 'pending',
            ]);
            return response()->json(['paymentUrl' => $paymentRequest->paymentUrl()]);
        } else {
            return response()->json(['error' => $paymentRequest->messages()]);
        }
    }

    public function callback(CallbackRequest $request)
    {
        $payment = Payment::where('gateway_result->transactionId', $request->transactionId())->first();
        $order = Order::find($payment->order_id);

        $verifiedPayment = $request
            ->amount($order->total_price)
            ->verify();

        if ($verifiedPayment->successful()) {
            $cardController = app(CardController::class);
            $uniqueCardNumber = $cardController->generateUniqueCardNumber();

            $card = Card::updateOrCreate(
                [
                    'order_id' => $payment->order_id,
                    'card_number' => $uniqueCardNumber,
                    'card_link' => route('downloadPdf', ['id' => $payment->order_id])
                ]
            );

            $order->update(['payment_status' => true]);

            $payment->update(['status' => 'successful']);

            $referenceId = $verifiedPayment->referenceId();
            $payment->forcefill([
                'gateway_result->reference_id' => $referenceId,
                'status' => 'success',
            ])->save();
            $pdfUrl = $cardController->createCard($order, $uniqueCardNumber);

            return response()->json([
                'message' => 'Payment was successful',
                'pdf_url' => $pdfUrl,
                'payment' => $payment
            ], 200);
        }

        if ($verifiedPayment->alreadyVerified()) {
            return response()->json(['error' => 'Payment already verified'], 400);
        }

        if ($verifiedPayment->failed()) {
            $payment->forcefill([
                'gateway_result->messages' => $verifiedPayment->messages(),
                'status' => 'failed',
            ])->save();
            $order->update(['status' => 'failed']);
            return response()->json([
                'message' => 'Payment was failed',
                'transaction' => $verifiedPayment]);
        }
    }


    public function paymentList()
    {
        $paymentList = Payment::with(['user', 'order.card'])->get();
        return response()->json($paymentList);
    }

    public function paymentFilter(Request $request)
    {
        $filterType = $request->filter_type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $paymentsQuery = Payment::query();

        switch ($filterType) {
            case 'daily':
                $paymentsQuery->whereDate('created_at', Carbon::today()->toDateString());
                break;

            case 'weekly':
                $paymentsQuery->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()]);
                break;

            case 'monthly':
                $paymentsQuery->whereMonth('created_at', Carbon::now()->month);
                break;

            case 'custom':
                $paymentsQuery->whereBetween('created_at', [Carbon::parse($startDate), Carbon::parse($endDate)]);
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
