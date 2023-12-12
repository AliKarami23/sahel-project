<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExtraditionAnswerRequest;
use App\Http\Requests\ExtraditionRequestsRequest;
use App\Models\Extradition;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class ExtraditionController extends Controller
{
    public function request(ExtraditionRequestsRequest $request)
    {
        $user = auth()->user();
        $orderId = $request->order_id;

        $productOrder = OrderProduct::where('order_id', $orderId)->first();

        if ($productOrder) {
            $productId = $productOrder->product_id;
            $product = Product::find($productId);

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }
            $extraditionPercent = $product->extradition_percent / 100;

            $order = Order::find($orderId);
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $price = $order->total_price;
                $final_price = $price * $extraditionPercent;

            $extradition = Extradition::create($request->merge([
                'user_id' => $user->id,
                'price' => $final_price,
            ])->all());


            return response()->json([
                'message' => 'Your request has been successfully registered and is being tracked',
                'extradition' => $extradition,
            ]);
        } else {
            return response()->json(['error' => 'OrderProduct not found'], 404);
        }
    }


    public function list()
    {
        $extraditions = Extradition::with(['user:id,full_name', 'order:id,total_price'])->get();

        $formattedExtraditions = $extraditions->map(function ($extradition) {
            return [
                'extradition_id' => $extradition->id,
                'user_full_name' => $extradition->user->full_name,
                'order_total_price' => $extradition->price,
            ];
        });

        return response()->json([
            'extraditions' => $formattedExtraditions,
        ]);
    }

    public function show($id)
    {
        $extradition = Extradition::with(['order:id,total_price'])->findOrFail($id);

        if (!$extradition) {
            return response()->json(['error' => 'Extradition not found'], 404);
        }
        $price = $extradition->price;
        return response()->json(['order_total_price' => $price]);
    }


    public function answer(ExtraditionAnswerRequest $request, $id)
    {
        try {
            $extradition = Extradition::findOrFail($id);
            $user = $extradition->user;
            $card = $extradition->order->card;
            $order = $extradition->order;
            $payment = $extradition->order->payment;
            $price = $extradition->price;
            if ($card && $user && $order && $payment) {
                $pdfPath = 'tickets/' . $order->id . '_ticket.pdf';

                if (Storage::exists($pdfPath)) {
                    Storage::delete($pdfPath);
                }

                $card->delete();
                $order->delete();
                $payment->update(['status' => 'failed']);
            }

            $phoneNumber = $user->phone_number;
            $answer = $request->answer;

            $smsController = new SmsController();
            $smsController->extradition($phoneNumber, $answer, $price);

            $extradition->update([
                'status' => 'Paid',
                'answer' => $request->answer
            ]);

            return response()->json([
                'message' => 'The money has been returned successfully and the SMS has been sent successfully',
                'extradition' => $extradition
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Extradition not found'], 404);
        }
    }
}
