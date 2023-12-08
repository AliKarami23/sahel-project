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
    public function CreateCard(Order $order, $uniqueCardNumber)
    {
        try {

            $orderDetails = Order::with(['reserves', 'reserves.sans', 'reserves.sans.product'])->find($order->id);


            $cardNumber = $uniqueCardNumber;
            $cardLink = $orderDetails->cards->first()->Card_Link;

            $reservation = $orderDetails->reserves->first();
            $ticketsSoldMan = $reservation->Tickets_Sold_Man;
            $ticketsSoldWoman = $reservation->Tickets_Sold_Woman;

            $sans = $reservation->sans;
            $sansStart = $sans->Start;
            $sansEnd = $sans->End;
            $sansDate = $sans->Date;

            $pdf = PDF::loadView('pdf.ticket', compact('cardNumber', 'cardLink', 'ticketsSoldMan', 'ticketsSoldWoman', 'sansStart', 'sansEnd', 'sansDate'));

            $pdfPath = 'tickets/' . $orderDetails->id . '_ticket.pdf';
            Storage::put($pdfPath, $pdf->output());

            $pdfUrl = Storage::url($pdfPath);

            return $pdfUrl;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generateUniqueCardNumber()
    {
        try {
            $uniqueCardNumber = null;

            while (!$uniqueCardNumber) {
                $randomNumber = random_int(1000000, 9999999);

                $existingOrder = Order::whereHas('cards', function ($query) use ($randomNumber) {
                    $query->where('Card_Number', $randomNumber);
                })->first();

                if (!$existingOrder) {
                    $uniqueCardNumber = $randomNumber;
                }
            }
            return $uniqueCardNumber;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function UserTickets()
    {
        try {
            $userOrders = Auth::user()->orders()->with('cards')->get();

            return response()->json($userOrders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function DownloadPdf($id)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function FilterCard(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function AllTickets()
    {
        try {
            $orders = Order::with(['reserves.sans', 'reserves.sans.product', 'cards'])
                ->get();

            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
