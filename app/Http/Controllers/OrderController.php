<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Sans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function Create(Request $request)
    {
        $Total_Price = 0;
        $updatedTicketsSold = [];
        $allReservations = [];
        $allSans = [];
        $user = optional(Auth::user());
        $order = Order::create([
            'user_id' => $user->id,
            "product_id" => $request->product_id,
            "Total_Price" => $Total_Price
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

            if ($productSans['Capacity_Man'] <= $Sans->Capacity_remains_Man && $productSans['Capacity_Woman'] <= $Sans->Capacity_remains_Woman) {

                $Total_Price += ($product->Price * $productSans['Capacity_Man']) + ($product->Price * $productSans['Capacity_Woman']);

                $user = optional(Auth::user());
                $reservationsData = [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'sans_id' => $Sans->id,
                    'product_id' => $productSans['product_id'],
                    'Tickets_Sold_Man' => $productSans['Capacity_Man'],
                    'Tickets_Sold_Woman' => $productSans['Capacity_Woman'],
                ];
                $Reservation = Reservation::create($reservationsData);
                $allReservations[] = $Reservation;


                $Tickets_Sold_Men = $Sans->Tickets_Sold_Man + $productSans['Capacity_Man'];
                $Tickets_Sold_Woman = $Sans->Tickets_Sold_Woman + $productSans['Capacity_Woman'];

                $updatedTicketsSold[$productSans['product_id']][$productSans['sans_id']] = [
                    'Tickets_Sold_Man' => $Tickets_Sold_Men,
                    'Tickets_Sold_Woman' => $Tickets_Sold_Woman,
                ];

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
            "Total_Price" => $Total_Price
        ]);

        foreach ($updatedTicketsSold as $productId => $sansData) {
            foreach ($sansData as $sansId => $ticketsSold) {
                $Sans = Sans::where('product_id', $productId)->where('id', $sansId)->first();
                $Reservation = Reservation::where('product_id', $productId)->first();

                $Capacity_remains_Man = $Sans->Capacity_remains_Man - $Reservation->Tickets_Sold_Man;
                $Capacity_remains_Woman = $Sans->Capacity_remains_Woman - $Reservation->Tickets_Sold_Woman;
                if ($Capacity_remains_Man >= 0 && $Capacity_remains_Woman >= 0) {
                    $Sans->update([
                        'Capacity_remains_Man' => $Capacity_remains_Man,
                        'Capacity_remains_Woman' => $Capacity_remains_Woman,
                    ]);
                }else{
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

    public function Edit(Request $request, $id)
    {

        $Total_Price = 0;
        $updatedTicketsSold = [];
        $allReservations = [];
        $allSans = [];

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        Reservation::where('order_id', $order->id)->delete();

        foreach (($request->json('product')) as $productSans) {
            $product = Product::find($productSans['product_id']);

            if (!$product) {
                return response()->json(['message' => 'Product with ID ' . $productSans['product_id'] . ' not found.'], 404);
            }

            $Sans = Sans::where('product_id', $productSans['product_id'])->first();

            if (!$Sans) {
                return response()->json(['message' => 'Sans record not found for the given product and sans ID.'], 404);
            }

            if ($productSans['Capacity_Man'] <= $Sans->Capacity_remains_Man && $productSans['Capacity_Woman'] <= $Sans->Capacity_remains_Woman) {
                $Total_Price += ($product->Price * $productSans['Capacity_Man']) + ($product->Price * $productSans['Capacity_Woman']);

                $user = optional(Auth::user());
                $reservationsData = [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'sans_id' => $Sans->id,
                    'product_id' => $productSans['product_id'],
                    'Tickets_Sold_Man' => $productSans['Capacity_Man'],
                    'Tickets_Sold_Woman' => $productSans['Capacity_Woman'],
                ];
                $Reservation = Reservation::create($reservationsData);
                $allReservations[] = $Reservation;

                $Tickets_Sold_Men = $Sans->Tickets_Sold_Man + $productSans['Capacity_Man'];
                $Tickets_Sold_Woman = $Sans->Tickets_Sold_Woman + $productSans['Capacity_Woman'];

                $updatedTicketsSold[$productSans['product_id']][$productSans['sans_id']] = [
                    'Tickets_Sold_Man' => $Tickets_Sold_Men,
                    'Tickets_Sold_Woman' => $Tickets_Sold_Woman,
                ];
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
            "Total_Price" => $Total_Price,
            "product_id" => $request->product_id,
        ]);

        foreach ($updatedTicketsSold as $productId => $sansData) {
            foreach ($sansData as $sansId => $ticketsSold) {

                $Sans = Sans::where('product_id', $productId)
                    ->where('id', $sansId)
                    ->first();


                if ($Sans) {
                    $totalTicketsSoldMan = Reservation::where('product_id', $productId)
                        ->where('sans_id', $sansId)
                        ->sum('Tickets_Sold_Man');

                    $totalTicketsSoldWoman = Reservation::where('product_id', $productId)
                        ->where('sans_id', $sansId)
                        ->sum('Tickets_Sold_Woman');

                    $newCapacityRemainsMan = $Sans->Capacity_Man - $totalTicketsSoldMan;
                    $newCapacityRemainsWoman = $Sans->Capacity_Woman - $totalTicketsSoldWoman;

                    if ($newCapacityRemainsMan >= 0 && $newCapacityRemainsWoman >= 0) {
                        $Sans->update([
                            'Capacity_remains_Man' => $newCapacityRemainsMan,
                            'Capacity_remains_Woman' => $newCapacityRemainsWoman,
                        ]);
                    }else{
                        return response()->json(['message' => 'There is not enough capacity to reserve.'], 400);
                    }
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

    public function Show($id)
    {

        $Order = Order::find($id);

        if (!$Order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json([
            'Order' => $Order
        ]);

    }

    public function delete($id)
    {
        $user = Auth::user();

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($user->hasRole('Admin') || $user->id === $order->user_id) {
            $order->delete();
            return response()->json(['message' => 'Order deleted successfully']);
        } else {
            return response()->json(['message' => 'Unauthorized to delete this order'], 403);
        }
    }

}
