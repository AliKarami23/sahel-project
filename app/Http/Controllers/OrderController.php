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
    public function create(Request $request)
    {
        $totalPrice = 0;
        $updatedTicketsSold = [];
        $allReservations = [];
        $allSans = [];
        $user = optional(Auth::user());
        $order = Order::create([
            'user_id' => $user->id,
            "product_id" => $request->product_id,
            "total_price" => $totalPrice
        ]);

        foreach ($request->json('product') as $productSans) {
            $product = Product::find($productSans['product_id']);

            if (!$product) {
                return response()->json(['error' => 'Product with ID ' . $productSans['product_id'] . ' not found.'], 404);
            }

            $sans = Sans::where('product_id', $productSans['product_id'])->first();

            if (!$sans) {
                return response()->json(['error' => 'Sans record not found for the given product and sans ID.'], 404);
            }

            if ($productSans['capacity_man'] <= $sans->capacity_remains_man && $productSans['capacity_woman'] <= $sans->capacity_remains_woman) {
                $totalPrice += ($product->price * $productSans['capacity_man']) + ($product->price * $productSans['capacity_woman']);

                $user = optional(Auth::user());
                $reservationsData = [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'sans_id' => $sans->id,
                    'product_id' => $productSans['product_id'],
                    'tickets_sold_man' => $productSans['capacity_man'],
                    'tickets_sold_woman' => $productSans['capacity_woman'],
                ];
                $reservation = Reservation::create($reservationsData);
                $allReservations[] = $reservation;

                $ticketsSoldMen = $sans->tickets_sold_man + $productSans['capacity_man'];
                $ticketsSoldWoman = $sans->tickets_sold_woman + $productSans['capacity_woman'];

                $updatedTicketsSold[$productSans['product_id']][$productSans['sans_id']] = [
                    'tickets_sold_man' => $ticketsSoldMen,
                    'tickets_sold_woman' => $ticketsSoldWoman,
                ];
            } else {
                return response()->json(['error' => 'There is not enough capacity to reserve.'], 400);
            }
        }

        foreach ($updatedTicketsSold as $productId => $sansData) {
            foreach ($sansData as $sansId => $ticketsSold) {
                $sans = Sans::where('product_id', $productId)->where('id', $sansId)->first();
                $allSans[] = $sans;
            }
        }

        $order->update([
            "total_price" => $totalPrice
        ]);

        foreach ($updatedTicketsSold as $productId => $sansData) {
            foreach ($sansData as $sansId => $ticketsSold) {
                $sans = Sans::where('product_id', $productId)->where('id', $sansId)->first();
                $reservation = Reservation::where('product_id', $productId)->first();

                $capacityRemainsMan = $sans->capacity_remains_man - $reservation->tickets_sold_man;
                $capacityRemainsWoman = $sans->capacity_remains_woman - $reservation->tickets_sold_woman;
                if ($capacityRemainsMan >= 0 && $capacityRemainsWoman >= 0) {
                    $sans->update([
                        'capacity_remains_man' => $capacityRemainsMan,
                        'capacity_remains_woman' => $capacityRemainsWoman,
                    ]);
                } else {
                    return response()->json(['error' => 'There is not enough capacity to reserve.'], 400);
                }
            }
        }

        return response()->json([
            'message' => 'The order and reservation have been successfully completed.',
            'order' => $order,
            'reservations' => $allReservations,
            'sans' => $allSans
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $totalPrice = 0;
        $updatedTicketsSold = [];
        $allReservations = [];
        $allSans = [];

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        Reservation::where('order_id', $order->id)->delete();

        foreach ($request->json('product') as $productSans) {
            $product = Product::find($productSans['product_id']);

            if (!$product) {
                return response()->json(['error' => 'Product with ID ' . $productSans['product_id'] . ' not found.'], 404);
            }

            $sans = Sans::where('product_id', $productSans['product_id'])->first();

            if (!$sans) {
                return response()->json(['error' => 'Sans record not found for the given product and sans ID.'], 404);
            }

            if ($productSans['capacity_man'] <= $sans->capacity_remains_man && $productSans['capacity_woman'] <= $sans->capacity_remains_woman) {
                $totalPrice += ($product->price * $productSans['capacity_man']) + ($product->price * $productSans['capacity_woman']);

                $user = optional(Auth::user());
                $reservationsData = [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'sans_id' => $sans->id,
                    'product_id' => $productSans['product_id'],
                    'tickets_sold_man' => $productSans['capacity_man'],
                    'tickets_sold_woman' => $productSans['capacity_woman'],
                ];
                $reservation = Reservation::create($reservationsData);
                $allReservations[] = $reservation;

                $ticketsSoldMen = $sans->tickets_sold_man + $productSans['capacity_man'];
                $ticketsSoldWoman = $sans->tickets_sold_woman + $productSans['capacity_woman'];

                $updatedTicketsSold[$productSans['product_id']][$productSans['sans_id']] = [
                    'tickets_sold_man' => $ticketsSoldMen,
                    'tickets_sold_woman' => $ticketsSoldWoman,
                ];
            } else {
                return response()->json(['error' => 'There is not enough capacity to reserve.'], 400);
            }
        }

        foreach ($updatedTicketsSold as $productId => $sansData) {
            foreach ($sansData as $sansId => $ticketsSold) {
                $sans = Sans::where('product_id', $productId)->where('id', $sansId)->first();
                $allSans[] = $sans;
            }
        }

        $order->update([
            "total_price" => $totalPrice,
            "product_id" => $request->product_id,
        ]);

        foreach ($updatedTicketsSold as $productId => $sansData) {
            foreach ($sansData as $sansId => $ticketsSold) {

                $sans = Sans::where('product_id', $productId)
                    ->where('id', $sansId)
                    ->first();

                if ($sans) {
                    $totalTicketsSoldMan = Reservation::where('product_id', $productId)
                        ->where('sans_id', $sansId)
                        ->sum('tickets_sold_man');

                    $totalTicketsSoldWoman = Reservation::where('product_id', $productId)
                        ->where('sans_id', $sansId)
                        ->sum('tickets_sold_woman');

                    $newCapacityRemainsMan = $sans->capacity_man - $totalTicketsSoldMan;
                    $newCapacityRemainsWoman = $sans->capacity_woman - $totalTicketsSoldWoman;

                    if ($newCapacityRemainsMan >= 0 && $newCapacityRemainsWoman >= 0) {
                        $sans->update([
                            'capacity_remains_man' => $newCapacityRemainsMan,
                            'capacity_remains_woman' => $newCapacityRemainsWoman,
                        ]);
                    } else {
                        return response()->json(['error' => 'There is not enough capacity to reserve.'], 400);
                    }
                }
            }
        }

        return response()->json([
            'message' => 'The order and reservation have been successfully completed.',
            'order' => $order,
            'reservations' => $allReservations,
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
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'order' => $order
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
            $order->delete();
            return response()->json(['message' => 'Order deleted successfully']);
        } else {
            return response()->json(['message' => 'Unauthorized to delete this order'], 403);
        }
    }


}


