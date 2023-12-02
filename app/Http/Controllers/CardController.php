<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    public function CreateCard(Order $order, $uniqueCardNumber)
    {
        $orderDetails = Order::with(['reserves', 'reserves.sans', 'reserves.sans.product'])->find($order->id);

        // Extract relevant information

        $cardNumber = $uniqueCardNumber;
        $cardLink = $orderDetails->cards->first()->Card_Link;

        $reservation = $orderDetails->reserves->first(); // Assuming there's only one reservation per order
        $ticketsSoldMan = $reservation->Tickets_Sold_Man;
        $ticketsSoldWoman = $reservation->Tickets_Sold_Woman;

        // Extract Sans information from the reservation
        $sans = $reservation->sans;
        $sansStart = $sans->Start;
        $sansEnd = $sans->End;
        $sansDate = $sans->Date;

        // Create PDF
        $pdf = PDF::loadView('pdf.ticket', compact('cardNumber', 'cardLink', 'ticketsSoldMan', 'ticketsSoldWoman', 'sansStart', 'sansEnd', 'sansDate'));

        // Store PDF in storage
        $pdfPath = 'tickets/' . $orderDetails->id . '_ticket.pdf';
        Storage::put($pdfPath, $pdf->output());

        // Provide the user with a link to download the PDF
        $pdfUrl = Storage::url($pdfPath);

        return $pdfUrl;
    }

    public function generateUniqueCardNumber()
    {
        $uniqueCardNumber = null;

        while (!$uniqueCardNumber) {
            $randomNumber = random_int(1000000, 9999999);

            $existingOrder = Order::whereHas('cards', function ($query) use ($randomNumber) {
                $query->where('Card_Number', $randomNumber);
            })->first();

            // If the number doesn't exist, use it as the unique card number
            if (!$existingOrder) {
                $uniqueCardNumber = $randomNumber;
            }
        }

        return $uniqueCardNumber;
    }
}
