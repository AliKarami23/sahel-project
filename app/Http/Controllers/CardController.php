<?php

namespace App\Http\Controllers;

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


        $cardNumber = $uniqueCardNumber;
        $cardLink = $orderDetails->cards->first()->card_Link;

        $reservation = $orderDetails->reserves->first();
        $ticketsSoldMan = $reservation->tickets_sold_Man;
        $ticketsSoldWoman = $reservation->tickets_sold_Woman;

        $sans = $reservation->sans;
        $sansStart = $sans->start;
        $sansEnd = $sans->end;
        $sansDate = $sans->date;

        $pdf = PDF::loadView('pdf.ticket', compact('cardNumber', 'cardLink', 'ticketsSoldMan', 'ticketsSoldWoman', 'sansStart', 'sansEnd', 'sansDate'));

        $pdfPath = 'tickets/' . $orderDetails->id . '_ticket.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        return $pdfUrl;
    }

    public function generateUniqueCardNumber()
    {
        $uniqueCardNumber = null;

        while (!$uniqueCardNumber) {
            $randomNumber = random_int(1000000, 9999999);

            $existingOrder = Order::whereHas('cards', function ($query) use ($randomNumber) {
                $query->where('card_Number', $randomNumber);
            })->first();

            if (!$existingOrder) {
                $uniqueCardNumber = $randomNumber;
            }
        }

        return $uniqueCardNumber;
    }

    public function userTickets()
    {
        $userOrders = Auth::user()->orders()->with('cards')->get();

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

        $orders = Order::with(['reserves.sans', 'reserves.sans.product', 'cards'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereHas('reserves.sans', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('Date', [$startDate, $endDate]);
                });
            })
            ->when($cardNumber, function ($query) use ($cardNumber) {
                $query->whereHas('cards', function ($query) use ($cardNumber) {
                    $query->where('Card_Number', $cardNumber);
                });
            })
            ->get();

        return response()->json($orders);
    }

    public function allTickets()
    {
        $orders = Order::with(['reserves.sans', 'reserves.sans.product', 'cards'])
            ->get();

        return response()->json($orders);
    }

}
