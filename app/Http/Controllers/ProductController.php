<?php

namespace App\Http\Controllers;

use App\Models\extradition;
use App\Models\Product;
use App\Models\Sans;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function Create(Request $request){

        if ($request->Discount_Type == 'Percent') {
            $Discounted_price = $request->Price - ($request->Price * $request->Discount_Amount / 100);
        } elseif ($request->Discount_Type == 'Amount') {
            $Discounted_price = $request->Price - $request->Discount_Amount;
        } else {
            $Discounted_price = $request->Price;
        }

        $Capacity_Total = $request->Capacity_Men + $request->Capacity_Women;

        $product = Product::create([
            'Title' => $request->Title,
            'Price' => $request->Price,
            'Discount' => $request->Discount,
            'Discount_Amount' => $request->Discount_Amount,
            'Discount_Type' => $request->Discount_Type,
            'Age_Limit' => $request->Age_Limit,
            'Age_Limit_Value' => $request->Age_Limit_Value,
            'Total_Start' => $request->Total_Start,
            'Total_End' => $request->Total_End,
            'Break_Time' => $request->Break_Time,
            'Capacity_Men' => $request->Capacity_Men,
            'Capacity_Women' => $request->Capacity_Women,
            'Capacity_Total' => $Capacity_Total,
            'Tickets_Sold' => $request->Tickets_Sold,
            'Rules' => $request->Rules,
            'Description' => $request->Description,
            'Discounted_price' => $Discounted_price,
        ]);

        foreach ($request->json('sans') as $sansData) {
            Sans::create([
                'product_id' => $product->id,
                'Start' => $sansData['Start'],
                'End' => $sansData['End'],
                'Date' => $sansData['Date'],
                'Status' => $sansData['Status'],
            ]);
        }

        foreach ($request->json('extradition') as $extraditionData) {
            Extradition::create([
                'product_id' => $product->id,
                'extradition' => $extraditionData['extradition'],
                'extradition_Time' => $extraditionData['extradition_Time'],
                'extradition_Percent' => $extraditionData['extradition_Percent'],
            ]);
        }
        $product_back = $request->all();
        return response()->json([
            'message' => 'Product Added',
            'product' => $product_back
            ]);
    }


    public function Edit(Request $request, $id){

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($request->Discount_Type == 'Percent') {
            $Discounted_price = $request->Price - ($request->Price * $request->Discount_Amount / 100);
        } elseif ($request->Discount_Type == 'Amount') {
            $Discounted_price = $request->Price - $request->Discount_Amount;
        } else {
            $Discounted_price = $request->Price;
        }

        $Capacity_Total = $request->Capacity_Men + $request->Capacity_Women;

        $product->update([
            'Title' => $request->Title,
            'Price' => $request->Price,
            'Discount' => $request->Discount,
            'Discount_Amount' => $request->Discount_Amount,
            'Discount_Type' => $request->Discount_Type,
            'Age_Limit' => $request->Age_Limit,
            'Age_Limit_Value' => $request->Age_Limit_Value,
            'Total_Start' => $request->Total_Start,
            'Total_End' => $request->Total_End,
            'Break_Time' => $request->Break_Time,
            'Capacity_Men' => $request->Capacity_Men,
            'Capacity_Women' => $request->Capacity_Women,
            'Capacity_Total' => $Capacity_Total,
            'Tickets_Sold' => $request->Tickets_Sold,
            'Rules' => $request->Rules,
            'Description' => $request->Description,
            'Discounted_price' => $Discounted_price,
        ]);

        $product->sans()->delete();

        foreach ($request->json('sans') as $sansData) {
            Sans::create([
                'product_id' => $product->id,
                'Start' => $sansData['Start'],
                'End' => $sansData['End'],
                'Date' => $sansData['Date'],
                'Status' => $sansData['Status'],
            ]);
        }

        $product->extraditions()->delete();

        foreach ($request->json('extradition') as $extraditionData) {
            Extradition::create([
                'product_id' => $product->id,
                'extradition' => $extraditionData['extradition'],
                'extradition_Time' => $extraditionData['extradition_Time'],
                'extradition_Percent' => $extraditionData['extradition_Percent'],
            ]);
        }

        return response()->json([
            'message' => 'Product Updated',
            'product' => $product
        ]);
    }



    public function List()
    {
        $products = Product::with(['sans', 'extraditions'])->get();

        return response()->json($products);
    }

    public function Delete($id)
    {
        $product = Product::findOrFail($id);

        $product->sans()->delete();
        $product->extraditions()->delete();

        $product->delete();

        return response()->json(['message' => 'Product Deleted']);
    }

}
