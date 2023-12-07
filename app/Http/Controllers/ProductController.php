<?php

namespace App\Http\Controllers;

use App\Models\Extradition;
use App\Models\Product;
use App\Models\Sans;
use Illuminate\Http\Request;
use Modules\Comment\app\Models\Comment;

class ProductController extends Controller
{


    public function Create(Request $request)
    {

        if ($request->Discount_Type == 'Percent') {
            $Discount = 'Percent';
            $Discounted_price = $request->Price - ($request->Price * $request->Discount_Amount / 100);
        } elseif ($request->Discount_Type == 'Amount') {
            $Discount = 'Amount';
            $Discounted_price = $request->Price - $request->Discount_Amount;
        } else {
            $Discount = null;
            $Discounted_price = $request->Price;
        }

        $Capacity_Total = $request->Capacity_Man + $request->Capacity_Woman;


        $productData = array_merge($request->all(), [
            'Capacity_Total' => $Capacity_Total,
            'Discounted_price' => $Discounted_price,
        ]);

        $product = Product::create($productData);


        foreach ($request->json('sans') as $sansData) {
            Sans::create([
                'product_id' => $product->id,
                'Start' => $sansData['Start'],
                'End' => $sansData['End'],
                'Date' => $sansData['Date'],
                'Capacity_Man' => $sansData['Capacity_Man'],
                'Capacity_Woman' => $sansData['Capacity_Woman'],
                'Capacity_remains_Man' => $sansData['Capacity_Man'],
                'Capacity_remains_Woman' => $sansData['Capacity_Woman'],
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
    public function Edit(Request $request, $id)
    {

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($request->Discount_Type == 'Percent') {
            $Discount = 'Percent';
            $Discounted_price = $request->Price - ($request->Price * $request->Discount_Amount / 100);
        } elseif ($request->Discount_Type == 'Amount') {
            $Discount = 'Amount';
            $Discounted_price = $request->Price - $request->Discount_Amount;
        } else {
            $Discount = null;
            $Discounted_price = $request->Price;
        }

        $Capacity_Total = $request->Capacity_Man + $request->Capacity_WoMan;

        $productData = $product->toArray();
        $mergedData = array_merge($productData, [
            'Capacity_Total' => $Capacity_Total,
            'Discounted_price' => $Discounted_price,
        ]);
        $product->update($mergedData);

        $product->sans()->delete();

        foreach ($request->json('sans') as $sansData) {
            Sans::create([
                'product_id' => $product->id,
                'Start' => $sansData['Start'],
                'End' => $sansData['End'],
                'Date' => $sansData['Date'],
                'Capacity_Man' => $sansData['Capacity_Man'],
                'Capacity_Woman' => $sansData['Capacity_Woman'],
                'Capacity_remains_Man' => $sansData['Capacity_Man'],
                'Capacity_remains_Woman' => $sansData['Capacity_Woman'],
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

        $product_back = $request->all();
        return response()->json([
            'message' => 'Product Updated',
            'product' => $product_back
        ]);
    }

    public function show($id)
    {

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $comments = Comment::where('product_id', $id)
            ->where('Status', 'Active')
            ->get();

        return response()->json([
            'product' => $product,
            'comments' => $comments
        ]);
    }


    public function List()
    {
        $products = Product::get(['id', 'title']);

        return response()->json($products);
    }

    public function Delete($id)
    {
        if ($product = Product::find($id)) {
            $product->sans()->delete();
            $product->extraditions()->delete();

            $product->delete();

            return response()->json(['message' => 'Product Deleted']);
        } else {
            return response()->json(['message' => 'Product not find']);
        }

    }

}
