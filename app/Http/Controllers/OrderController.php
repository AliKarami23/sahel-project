<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Sans;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create(OrderRequest $request)
    {
        $total_price = 0;
        $updatedTicketsSold = [];
        $allReservations = [];
        $allSans = [];
        $user = optional(Auth::user());
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $total_price
        ]);


        foreach (($request->json('product')) as $productSans) {
            $product = Product::find($productSans['product_id']);


            if (!$product) {
                return response()->json(['message' => 'Product with ID ' . $productSans['product_id'] . ' not found.'], 404);
            }

            $Sans = Sans::where('product_id', $productSans['product_id'])->first();

            if (!$Sans) {
                return response()->json(['message' => 'Sans record not found for the given product and sans ID.'], 404);
            }
            if ($Sans->status == 'Inactive') {
                return response()->json(['message' => 'The Sans is inactive and cannot be reserved.'], 400);
            }
            if ($productSans['capacity_man'] <= $Sans->capacity_remains_man && $productSans['capacity_woman'] <= $Sans->capacity_remains_woman) {

                $total_price += ($product->price * $productSans['capacity_man']) + ($product->price * $productSans['capacity_woman']);

                $user = optional(Auth::user());
                $reservationsData = [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'sans_id' => $Sans->id,
                    'product_id' => $productSans['product_id'],
                    'tickets_sold_man' => $productSans['capacity_man'],
                    'tickets_sold_woman' => $productSans['capacity_woman'],
                ];
                $Reservation = Reservation::create($reservationsData);
                $allReservations[] = $Reservation;


                $tickets_sold_men = $Sans->tickets_sold_man + $productSans['capacity_man'];
                $tickets_sold_woman = $Sans->tickets_sold_woman + $productSans['capacity_woman'];

                $updatedTicketsSold[$productSans['product_id']][$productSans['sans_id']] = [
                    'tickets_sold_man' => $tickets_sold_men,
                    'tickets_sold_woman' => $tickets_sold_woman,
                ];

                $order->products()->create([
                    'product_id' => $productSans['product_id'],
                ]);
            } else {
                return response()->json(['message' => 'There is not enough capacity to reserve.'], 400);
            }
        }

        foreach ($updatedTicketsSold as $productId => $sansData) {
            foreach ($sansData as $sansId => $ticketsSold) {
                $Sans = Sans::where('product_id', $productId)->where('id', $sansId)->first();
                $allSans[] = $Sans;
            }
        }

        $order->update([
            'total_price' => $total_price
        ]);

        foreach ($updatedTicketsSold as $productId => $sansData) {
            foreach ($sansData as $sansId => $ticketsSold) {
                $Sans = Sans::where('product_id', $productId)->where('id', $sansId)->first();

                // محاسبه تعداد کل بلیت‌ها
                $totalTicketsSoldMan = $Sans->tickets_sold_man + $ticketsSold['tickets_sold_man'];
                $totalTicketsSoldWoman = $Sans->tickets_sold_woman + $ticketsSold['tickets_sold_woman'];

                $capacity_remains_man = $Sans->capacity_remains_man - $totalTicketsSoldMan;
                $capacity_remains_woman = $Sans->capacity_remains_woman - $totalTicketsSoldWoman;

                if ($capacity_remains_man >= 0 && $capacity_remains_woman >= 0) {
                    $Sans->update([
                        'tickets_sold_man' => $totalTicketsSoldMan,
                        'tickets_sold_woman' => $totalTicketsSoldWoman,
                        'capacity_remains_man' => $capacity_remains_man,
                        'capacity_remains_woman' => $capacity_remains_woman,
                    ]);
                } else {
                    return response()->json(['message' => 'There is not enough capacity to reserve.'], 400);
                }
            }
        }


        return response()->json([
            'message' => 'The order and reservation have been successfully completed.',
            'order' => $order,
            'Reservation' => $allReservations,
            'sans' => $allSans
        ], 200);
    }

    public function edit(OrderRequest $request, $id)
    {
        $total_price = 0;
        $updatedTicketsSold = [];
        $allReservations = [];
        $allSans = [];

        $order = Order::findOrFail($id);

        Reservation::where('order_id', $order->id)->delete();

        foreach ($request->json('product') as $productSans) {
            $product = Product::findOrFail($productSans['product_id']);
            $Sans = Sans::where('product_id', $productSans['product_id'])
                ->where('id', $productSans['sans_id'])
                ->firstOrFail();

            if ($Sans->status == 'Inactive') {
                return response()->json(['message' => 'The Sans is inactive and cannot be reserved.'], 400);
            }
            if ($Sans->capacity_remains_man >= $productSans['capacity_man'] && $Sans->capacity_remains_woman >= $productSans['capacity_woman']) {
                $total_price += ($product->price * $productSans['capacity_man']) + ($product->price * $productSans['capacity_woman']);

                $user = optional(Auth::user());
                $reservationsData = [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'sans_id' => $Sans->id,
                    'product_id' => $productSans['product_id'],
                    'tickets_sold_man' => $productSans['capacity_man'],
                    'tickets_sold_woman' => $productSans['capacity_woman'],
                ];

                $Reservation = Reservation::create($reservationsData);
                $allReservations[] = $Reservation;

                $tickets_sold_men = $Sans->tickets_sold_man + $productSans['capacity_man'];
                $tickets_sold_woman = $Sans->tickets_sold_woman + $productSans['capacity_woman'];

                $updatedTicketsSold[$productSans['product_id']][$productSans['sans_id']] = [
                    'tickets_sold_man' => $tickets_sold_men,
                    'tickets_sold_woman' => $tickets_sold_woman,
                ];
            } else {
                return response()->json(['message' => 'There is not enough capacity to reserve.'], 400);
            }
        }

        foreach ($updatedTicketsSold as $productId => $sansData) {
            foreach ($sansData as $sansId => $ticketsSold) {
                $allSans[] = Sans::findOrFail($sansId);
            }
        }

        foreach ($updatedTicketsSold as $productId => $sansData) {
            foreach ($sansData as $sansId => $ticketsSold) {
                $Sans = Sans::findOrFail($sansId);
                $totalTicketsSoldMan = Reservation::where('product_id', $productId)
                    ->where('sans_id', $sansId)
                    ->value('tickets_sold_man');

                $totalTicketsSoldWoman = Reservation::where('product_id', $productId)
                    ->where('sans_id', $sansId)
                    ->value('tickets_sold_woman');

                $newCapacityRemainsMan = $Sans->capacity_man - $totalTicketsSoldMan;
                $newCapacityRemainsWoman = $Sans->capacity_woman - $totalTicketsSoldWoman;

                if ($newCapacityRemainsMan >= 0 && $newCapacityRemainsWoman >= 0) {
                    $Sans->update([
                        'capacity_remains_man' => $newCapacityRemainsMan,
                        'capacity_remains_woman' => $newCapacityRemainsWoman,
                    ]);
                } else {
                    return response()->json(['message' => 'There is not enough capacity to reserve.'], 400);
                }
            }
        }

        return response()->json([
            'message' => 'The order and reservation have been successfully completed.',
            'order' => $order,
            'Reservation' => $allReservations,
            'sans' => $allSans
        ], 200);
    }


    public function list()
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            $orders = Order::all();
        } else {
            $orders = Order::where('user_id', $user->id)->get();
        }

        return response()->json(['orders' => $orders]);
    }

    public function show($id)
    {

        $Order = Order::find($id);

        if (!$Order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json([
            'Order' => $Order
        ]);

    }

    public function destroy($id)
    {
        $user = Auth::user();

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($user->hasRole('Admin') || $user->id === $order->user_id) {
            $order->destroy();
            return response()->json(['message' => 'Order deleted successfully']);
        } else {
            return response()->json(['message' => 'Unauthorized to delete this order'], 403);
        }
    }

}
