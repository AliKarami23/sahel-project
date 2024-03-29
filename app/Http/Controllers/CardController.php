<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    public function createCard(Order $order, $uniqueCardNumber)
    {
        $orderDetails = Order::with(['reserves', 'reserves.sans', 'reserves.sans.product'])->find($order->id);

        $pdf = PDF::loadView('pdf.ticket', compact('orderDetails', 'uniqueCardNumber'));

        $pdfPath = 'tickets/' . $orderDetails->id . '_ticket.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        return $pdfUrl;
    }
    public function updateCard($order, $uniqueCardNumber)
    {
        $orderDetails = Order::with(['reserves', 'reserves.sans', 'reserves.sans.product'])->find($order->id);


        $pdfPath = 'tickets/' . $orderDetails->id . '_ticket.pdf';
        if (Storage::exists($pdfPath)) {
            Storage::delete($pdfPath);
        }

        $pdf = PDF::loadView('pdf.ticket', compact('orderDetails', 'uniqueCardNumber'));
        $newPdfPath = 'tickets/' . $orderDetails->id . '_ticket.pdf';
        Storage::put($newPdfPath, $pdf->output());

        $order->card->update(['card_number' => $uniqueCardNumber]);

        return Storage::url($newPdfPath);
    }

    public function generateUniqueCardNumber()
    {
        $uniqueCardNumber = null;

        while (!$uniqueCardNumber) {
            $randomNumber = random_int(1000000, 9999999);

            $existingOrder = Order::whereHas('card', function ($query) use ($randomNumber) {
                $query->where('card_Number', $randomNumber);
            })->first();

            if (!$existingOrder) {
                $uniqueCardNumber = $randomNumber;
            }
        }

        return $uniqueCardNumber;
    }

    public function userCard()
    {
        $userOrders = Auth::user()->orders()->with('card')->get();

        return response()->json($userOrders);
    }

    public function downloadPdf($id)
    {
        $order = Order::find($id);

        if (!$order) {
            abort(404, 'Order not found');
        }

        $pdfPath = 'tickets/' . $order->id . '_ticket.pdf';

        if (Storage::exists($pdfPath)) {
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="ticket.pdf"',
            ];

            return Response::download(storage_path("app/{$pdfPath}"), 'ticket.pdf', $headers);
        } else {
            abort(404, 'PDF not found');
        }

    }

    public function filterCard(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $cardNumber = $request->card_number;

        $orders = Order::with(['reserves.sans', 'reserves.sans.product', 'card'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereHas('reserves.sans', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('Date', [$startDate, $endDate]);
                });
            })
            ->when($cardNumber, function ($query) use ($cardNumber) {
                $query->whereHas('card', function ($query) use ($cardNumber) {
                    $query->where('Card_Number', $cardNumber);
                });
            })
            ->get();

        return response()->json($orders);
    }

    public function allCard()
    {
        $orders = Order::with(['user','reserves.sans', 'reserves.sans.product', 'card'])
            ->get();

        return response()->json($orders);
    }
    public function showCard($id)
    {
        $card = Card::find($id);

        if (!$card) {
            return response()->json(['error' => 'Card not found'], 404);
        }

        $order = $card->order;

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $user = $order->user;

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $reservations = $order->reserves;

        return response()->json([
            'user' => $user,
            'reservations' => $reservations,
        ]);
    }
}
