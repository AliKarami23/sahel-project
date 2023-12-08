<?php

namespace App\Http\Controllers;

use App\Models\Extradition;
use App\Models\Image;
use App\Models\Product;
use App\Models\Sans;
use App\Models\Video;
use Illuminate\Http\Request;
use Modules\Comment\app\Models\Comment;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        if (isset($request->image_id)) {
            $imageIds = is_array($request->image_id) ? $request->image_id : [$request->image_id];
            $images = [];

            foreach ($imageIds as $imageId) {
                $image = Image::findOrFail($imageId);
                $images[] = $image;
                $image->update([
                    'Status' => 'Active'
                ]);
            }
        } else {
            return response()->json(['error' => 'image_id is required.'], 400);
        }

        if (isset($request->video_id)) {
            $video = Video::findOrFail($request->video_id);
            $video->update([
                'Status' => 'Active'
            ]);
        } else {
            return response()->json(['error' => 'video_id is required.'], 400);
        }

        if (isset($request->imageMain_id)) {
            $imageMain = Image::findOrFail($request->imageMain_id);
            $imageMain->update([
                'Status' => 'Active'
            ]);
        } else {
            return response()->json(['error' => 'imageMain_id is required.'], 400);
        }

        if ($request->Discount_Type === 'Percent') {
            $Discount = 'Percent';
            $Discounted_price = $request->Price - ($request->Price * $request->Discount_Amount / 100);
        } elseif ($request->Discount_Type === 'Amount') {
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
            $Sans = Sans::create([
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
            $Extradition = Extradition::create([
                'product_id' => $product->id,
                'extradition' => $extraditionData['extradition'],
                'extradition_Time' => $extraditionData['extradition_Time'],
                'extradition_Percent' => $extraditionData['extradition_Percent'],
            ]);
        }

        return response()->json([
            'message' => 'Product Added',
            'product' => $product,
            'extradition' => $Extradition,
            'sans' => $Sans
        ]);
    }

    public function edit(Request $request, $id)
    {
        if (isset($request->image_id)) {
            $imageIds = is_array($request->image_id) ? $request->image_id : [$request->image_id];
            $images = [];

            foreach ($imageIds as $imageId) {
                $image = Image::findOrFail($imageId);
                $images[] = $image;
                $image->update([
                    'Status' => 'Active'
                ]);
            }
        } else {
            return response()->json(['error' => 'image_id is required.'], 400);
        }

        if (isset($request->video_id)) {
            $video = Video::findOrFail($request->video_id);
            $video->update([
                'Status' => 'Active'
            ]);
        } else {
            return response()->json(['error' => 'video_id is required.'], 400);
        }

        if (isset($request->imageMain_id)) {
            $imageMain = Image::findOrFail($request->imageMain_id);
            $imageMain->update([
                'Status' => 'Active'
            ]);
        } else {
            return response()->json(['error' => 'imageMain_id is required.'], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($request->Discount_Type === 'Percent') {
            $Discount = 'Percent';
            $Discounted_price = $request->Price - ($request->Price * $request->Discount_Amount / 100);
        } elseif ($request->Discount_Type === 'Amount') {
            $Discount = 'Amount';
            $Discounted_price = $request->Price - $request->Discount_Amount;
        } else {
            $Discount = null;
            $Discounted_price = $request->Price;
        }

        $Capacity_Total = $request->Capacity_Man + $request->Capacity_Woman;

        $productData = $product->toArray();
        $mergedData = array_merge($productData, [
            'Capacity_Total' => $Capacity_Total,
            'Discounted_price' => $Discounted_price,
        ]);
        $product->update($mergedData);

        $product->sans()->delete();

        foreach ($request->json('sans') as $sansData) {
            $Sans = Sans::create([
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
            $Extradition = Extradition::create([
                'product_id' => $product->id,
                'extradition' => $extraditionData['extradition'],
                'extradition_Time' => $extraditionData['extradition_Time'],
                'extradition_Percent' => $extraditionData['extradition_Percent'],
            ]);
        }

        return response()->json([
            'message' => 'Product Added',
            'product' => $product,
            'extradition' => $Extradition,
            'sans' => $Sans
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

        $MainImage = $product->imageMain_id;
        $imageIds = $product->image_id;
        $images = [];

        if ($imageIds) {
            foreach ($imageIds as $imageId) {
                $image = Image::find($imageId);
                if ($image) {
                    $images[] = $image->getMedia('*');
                }
            }
        }

        if ($MainImage) {
            $imageMain = Image::find($MainImage);
            $Main_image = $imageMain->getMedia('*');
        }
        $videoId = $product->video_id;
        if ($videoId) {
            $video = Video::find($videoId);
            $product_video = $video->getMedia('*');
        }

        return response()->json([
            'product' => $product,
            'comments' => $comments,
            'images' => $images ?? [],
            'imageMain' => $Main_image ?? [],
            'video' => $product_video ?? []
        ]);
    }


    public function list()
    {
        $products = Product::all();
        $productsWithImages = [];

        foreach ($products as $product) {
            $MainImage = $product->imageMain_id;
            $Main_image = [];

            if ($MainImage) {
                $image_Main = Image::find($MainImage);

                if ($image_Main) {
                    $Main_image = $image_Main->getMedia('*');
                }
            }

            $productDataItem = [
                'id' => $product->id,
                'title' => $product->title,
                'images' => $Main_image,
            ];

            $productsWithImages[] = $productDataItem;
        }

        return response()->json($productsWithImages);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $MainImage = $product->imageMain_id;
        $imageIds = $product->image_id;

        if ($imageIds) {
            foreach ($imageIds as $imageId) {
                $image = Image::find($imageId);
                if ($image) {
                    $image->update(['Status' => 'delete']);
                }
            }
        }

        if ($MainImage) {
            $imageMain = Image::find($MainImage);
            $imageMain->update(['Status' => 'delete']);
        }

        $videoId = $product->video_id;
        if ($videoId) {
            $video = Video::find($videoId);
            $video->update(['Status' => 'delete']);
        }

        $product->sans()->delete();
        $product->extraditions()->delete();
        $product->delete();

        return response()->json(['message' => 'Product Deleted']);
    }
}
