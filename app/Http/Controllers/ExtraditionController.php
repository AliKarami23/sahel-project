<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExtraditionAnswerRequest;
use App\Http\Requests\ExtraditionRequestsRequest;
use App\Models\Extradition;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Sans;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Morilog\Jalali\CalendarUtils;
use function Laravel\Prompts\error;

class ExtraditionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/projects",
     *      operationId="getProjectsList",
     *      tags={"Projects"},
     *      summary="Get list of projects",
     *      description="Returns list of projects",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    public function request(ExtraditionRequestsRequest $request)
    {
        $user = auth()->user();
        $reserveId = $request->reserve_id;
        $capacity_man = $request->capacity_man;
        $capacity_woman = $request->capacity_woman;

        $reserve = Reservation::find($reserveId);

        if ($capacity_man > $reserve->tickets_sold_man || $capacity_woman > $reserve->tickets_sold_woman) {
            return response()->json(['error' => 'The number sent is more than the number reserved'], 404);
        }
        if ($reserve) {
            $productId = $reserve->product_id;
            $product = Product::find($productId);

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }
            $extraditionPercent = $product->extradition_percent / 100;

            $order = $reserve->order;

            $price = $order->total_price;
            $final_price = $price * $extraditionPercent;

            $existingExtradition = Extradition::where('reserve_id', $reserveId)->first();

            if ($existingExtradition) {
                return response()->json(['error' => 'This reservation has already been requested'], 400);
            }

            $extradition = Extradition::create($request->merge([
                'user_id' => $user->id,
                'price' => $final_price,
            ])->all());

            return response()->json([
                'message' => 'Your request has been successfully registered and is being tracked',
                'extradition' => $extradition,
            ]);
        } else {
            return response()->json(['error' => 'Reservation not found'], 404);
        }
    }


    public function list()
    {
        $extraditions = Extradition::with(['user:id,full_name'])->get();

        return response()->json([
            'extraditions' => $extraditions,
        ]);
    }

    public function show($id)
    {
        $extradition = Extradition::with(['user:id,full_name'])->findOrFail($id);

        if (!$extradition) {
            return response()->json(['error' => 'Extradition not found'], 404);
        }
        $price = $extradition->price;
        return response()->json(['price' => $price,
            'extradition' => $extradition
        ]);
    }


    public function answer(ExtraditionAnswerRequest $request, $id)
    {
        try {
            $extradition = Extradition::with(['order', 'user'])->findOrFail($id);
            $order = $extradition->order;
            $reserve = Reservation::find($extradition->reserve_id);
            $sans = $reserve->sans;
            $product =Product::find($sans->product_id);
            $user = $extradition->user;
            $capacity_man = $extradition->capacity_man;
            $capacity_woman = $extradition->capacity_woman;

            if ($order && $order->card && $user) {
                $pdfPath = 'tickets/' . $order->id . '_ticket.pdf';

                if ($capacity_man == $reserve->tickets_sold_man && $capacity_woman == $reserve->tickets_sold_woman) {
                    $order->card->delete();
                    $order->delete();
                    if (Storage::exists($pdfPath)) {
                        Storage::delete($pdfPath);
                    }
                    $reserve->update(['status' => 'cancel']);
                } else {

                    $CardController = new CardController();
                    $uniqueCardNumber = $CardController->generateUniqueCardNumber();
                    $pdfUrl = $CardController->updateCard($order, $uniqueCardNumber);

                    $reserve->update([
                        'tickets_sold_man' => $capacity_man,
                        'tickets_sold_woman' => $capacity_woman,
                    ]);
                }


                $capacity_remains_man = $sans->capacity_remains_man + $capacity_man;
                $capacity_remains_woman = $sans->capacity_remains_woman + $capacity_woman;
                $sans->update([
                    'capacity_remains_man' => $capacity_remains_man,
                    'capacity_remains_woman' => $capacity_remains_woman,
                ]);

                $order_price = $product->price * ($capacity_man + $capacity_woman);
                $order->update(['price' => $order_price]);

                $phoneNumber = $user->phone_number;
                $answer = $request->answer;

                $smsController = new SmsController();
                $smsController->extradition($phoneNumber, $answer, $extradition->price);

                $extradition->update([
                    'status' => 'Paid',
                    'answer' => $answer,
                ]);
                return response()->json([
                    'message' => 'The money has been returned successfully and the SMS has been sent successfully',
                    'extradition' => $extradition,
                    'pdfUrl' => $pdfUrl ?? null
                ]);

            } else {
                return response()->json(['error' => 'Invalid order or user'], 400);
            }

        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Extradition not found'], 404);
        }
    }


    public function cancellationSans(Request $request)
    {
        $productId = $request->product_id;
        $sansId = $request->sans_id;

        $product = Product::select('id', 'title')->find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $sans = $product->sans()->where('id', $sansId)->first();

        if (!$sans) {
            return response()->json(['message' => 'Sans not found'], 404);
        }

        $sans->update(['status' => 'Inactive']);

        $reservations = Reservation::where('sans_id', $sansId)->get();
        $reserveData = [];

        foreach ($reservations as $reservation) {
            $user = $reservation->user;
            $phoneNumber = $user->phone_number;
            $full_name = $user->full_name;
            $date = CalendarUtils::strftime('Y-m-d', strtotime($sans->date));
            $start = $sans->start;
            $end = $sans->end;
            $titel = $product->titel;
            $reserveData[] = [
                'product' => $titel,
                'phoneNumber' => $phoneNumber,
                'full_name' => $full_name,
                'date' => $date,
                'start' => $start,
                'end' => $end,
            ];
            $smsController = new SmsController();
            $smsController->cancellationSans($phoneNumber, $full_name, $titel, $date, $start, $end);
        }

        return response()->json([
            'message' => 'Sans canceled successfully',
            'reserve' => $reserveData
        ], 200);
    }
}
