<?php

namespace App\Http\Controllers;

use App\Models\extradition;
use App\Models\Product;
use App\Models\Sans;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_image')->singleFile();
        $this->addMediaCollection('additional_images1')->singleFile();
        $this->addMediaCollection('additional_images2')->singleFile();
        $this->addMediaCollection('additional_images3')->singleFile();
        $this->addMediaCollection('additional_images4')->singleFile();
        $this->addMediaCollection('videos')->singleFile();
    }

    private function deleteAndSaveMedia($product, $request, $inputName, $collectionName, $mediaName)
    {
        $newMedia = $request->file($inputName);

        if ($newMedia instanceof UploadedFile) {
            $product->clearMediaCollection($collectionName);

            $mediaPath = $product->addMedia($newMedia)
                ->toMediaCollection($mediaName, $collectionName)
                ->getPath();
        }
    }

    public function Create(Request $request)
    {

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


        $mainImage = $request->file('main_image');
        $mainImagePath = $product->addMedia($mainImage)
            ->toMediaCollection('main_image', 'images')
            ->getPath();

        $additionalImages1 = $request->file('additional_images1');
        $additionalImages2 = $request->file('additional_images2');
        $additionalImages3 = $request->file('additional_images3');
        $additionalImages4 = $request->file('additional_images4');
        if (isset($additionalImages1)) {
            $additionalImagePath = $product->addMedia($additionalImages1)
                ->toMediaCollection('additional_images1', 'images')
                ->getPath();
        }
        if (isset($additionalImages2)) {
            $additionalImagePath = $product->addMedia($additionalImages2)
                ->toMediaCollection('additional_images2', 'images')
                ->getPath();
        }
        if (isset($additionalImages3)) {
            $additionalImagePath = $product->addMedia($additionalImages3)
                ->toMediaCollection('additional_images3', 'images')
                ->getPath();
        }
        if (isset($additionalImages4)) {
            $additionalImagePath = $product->addMedia($additionalImages4)
                ->toMediaCollection('additional_images4', 'images')
                ->getPath();
        }

        $video = $request->file('video');
        $videoPath = $product->addMedia($video)
            ->toMediaCollection('videos', 'videos')
            ->getPath();

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


    public function Edit(Request $request, $id)
    {

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

        $mainImage = $request->file('main_image');
        if (isset($mainImage)) {
            $mainImagePath = $product->addMedia($mainImage)
                ->toMediaCollection('main_image', 'images')
                ->getPath();
        }
        $additionalImages1 = $request->file('additional_images1');
        $additionalImages2 = $request->file('additional_images2');
        $additionalImages3 = $request->file('additional_images3');
        $additionalImages4 = $request->file('additional_images4');
        if (isset($additionalImages1)) {
            $this->deleteAndSaveMedia($product, $request, 'additional_images1', 'images', 'additional_images1');
            $additionalImagePath = $product->addMedia($additionalImages1)
                ->toMediaCollection('additional_images1', 'images')
                ->getPath();
        }
        if (isset($additionalImages2)) {
            $this->deleteAndSaveMedia($product, $request, 'additional_images2', 'images', 'additional_images2');
            $additionalImagePath = $product->addMedia($additionalImages2)
                ->toMediaCollection('additional_images2', 'images')
                ->getPath();
        }
        if (isset($additionalImages3)) {
            $this->deleteAndSaveMedia($product, $request, 'additional_images3', 'images', 'additional_images3');
            $additionalImagePath = $product->addMedia($additionalImages3)
                ->toMediaCollection('additional_images3', 'images')
                ->getPath();
        }
        if (isset($additionalImages4)) {
            $this->deleteAndSaveMedia($product, $request, 'additional_images4', 'images', 'additional_images4');
            $additionalImagePath = $product->addMedia($additionalImages4)
                ->toMediaCollection('additional_images4', 'images')
                ->getPath();
        }

        $video = $request->file('video');
        if (isset($video)) {
            $this->deleteAndSaveMedia($product, $request, 'video', 'videos', 'videos');
            $videoPath = $product->addMedia($video)
                ->toMediaCollection('videos', 'videos')
                ->getPath();
        }

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
