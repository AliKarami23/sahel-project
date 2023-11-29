<?php

namespace App\Http\Controllers;

use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function Payment(Request $request)
    {
        $order = Order::find($request->order_id);
        $user = optional(Auth::user());
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $data = [
            'order_id' => $order->id,
            'amount' => $order->Total_Price,
            'name' => $user->Full_Name,
            'phone' => $user->Phone_Number,
            'mail' => $user->Email,
            'callback' => '/Payment/CallBack',
        ];

        $client = new Client();

        try {
            $response = $client->post('https://api.idpay.ir/v1.1/payment', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-API-KEY' => '52b7cd0d-fef6-4da8-9a39-298c3f8fb9ad',
                    'X-SANDBOX' => 1,
                ],
                'json' => $data,
            ]);

            $responseData = json_decode($response->getBody(), true);

            return view('Payment.payment-result', ['data' => $responseData]);
        } catch (\Exception $e) {
            return view('Payment.payment-result', ['error' => $e->getMessage()]);
        }
    }
}
