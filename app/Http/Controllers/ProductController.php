<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Image;
use App\Models\Product;
use App\Models\Sans;
use App\Models\Video;
use Modules\Comment\app\Models\Comment;

class ProductController extends Controller
{
    public function create(ProductRequest $request)
    {
        if (isset($request->image_id)) {
            $imageIds = is_array($request->image_id) ? $request->image_id : [$request->image_id];
            $images = [];

            foreach ($imageIds as $imageId) {
                $image = Image::findOrFail($imageId);
                $images[] = $image;
            }
            $image->update([
                'status' => 'Active'
            ]);
        } else {
            return response()->json(['error' => 'image_id is required.'], 400);
        }


        if (isset($request->video_id)) {
            $video = Video::findOrFail($request->video_id);
            $video->update([
                'status' => 'Active'
            ]);
        } else {
            return response()->json(['error' => 'video_id is required.'], 400);
        }

        if (isset($request->image_main_id)) {
            $image_main = Image::findOrFail($request->image_main_id);
            $image_main->update([
                'status' => 'Active'
            ]);
        } else {
            return response()->json(['error' => 'image_id is required.'], 400);
        }

        if ($request->discount_type == 'Percent') {
            $Discount = 'Percent';
            $price = $request->price - ($request->price * $request->discount_amount / 100);
        } elseif ($request->discount_type == 'Amount') {
            $discount = 'Amount';
            $price = $request->price - $request->discount_amount;
        } else {
            $discount = null;
            $price = $request->price;
        }

        $capacity_total = $request->capacity_man + $request->capacity_woman;


        $productData = array_merge($request->all(), [
            'capacity_total' => $capacity_total,
            'price' => $price
        ]);

        $product = Product::create($productData);


        foreach ($request->json('sans') as $sansData) {
            Sans::create([
                'product_id' => $product->id,
                'start' => $sansData['start'],
                'end' => $sansData['end'],
                'date' => $sansData['date'],
                'capacity_man' => $sansData['capacity_man'],
                'capacity_woman' => $sansData['capacity_woman'],
                'capacity_remains_man' => $sansData['capacity_man'],
                'capacity_remains_woman' => $sansData['capacity_woman'],
            ]);
        }

        return response()->json([
            'message' => 'Product Added',
            'product' => $product
        ]);
    }

    public function edit(ProductRequest $request, $id)
    {
        if (isset($request->image_id)) {
            $imageIds = is_array($request->image_id) ? $request->image_id : [$request->image_id];
            $images = [];

            foreach ($imageIds as $imageId) {
                $image = Image::findOrFail($imageId);
                $images[] = $image;
            }
            $image->update([
                'status' => 'Active'
            ]);
        } else {
            return response()->json(['error' => 'image_id is required.'], 400);
        }


        if (isset($request->video_id)) {
            $video = Video::findOrFail($request->video_id);
            $video->update([
                'status' => 'Active'
            ]);
        } else {
            return response()->json(['error' => 'video_id is required.'], 400);
        }

        if (isset($request->image_main_id)) {
            $image_main = Image::findOrFail($request->image_main_id);
            $image_main->update([
                'status' => 'Active'
            ]);
        } else {
            return response()->json(['error' => 'image_id is required.'], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($request->discount_type == 'Percent') {
            $Discount = 'Percent';
            $price = $request->price - ($request->price * $request->discount_amount / 100);
        } elseif ($request->discount_type == 'Amount') {
            $discount = 'Amount';
            $price = $request->price - $request->discount_amount;
        } else {
            $discount = null;
            $price = $request->price;
        }


        $capacity_total = $request->capacity_man + $request->capacity_woman;

        $productData = $product->toArray();
        $mergedData = array_merge($productData, [
            'capacity_total' => $capacity_total,
            'price' => $price
        ]);
        $product->update($mergedData);

        $product->sans()->delete();

        foreach ($request->json('sans') as $sansData) {
            Sans::create([
                'product_id' => $product->id,
                'start' => $sansData['start'],
                'end' => $sansData['end'],
                'date' => $sansData['date'],
                'capacity_man' => $sansData['capacity_man'],
                'capacity_woman' => $sansData['capacity_woman'],
                'capacity_remains_man' => $sansData['capacity_man'],
                'capacity_remains_woman' => $sansData['capacity_woman'],
            ]);
        }

        return response()->json([
            'message' => 'Product Updated',
            'product' => $product
        ]);
    }

    public function show($id)
    {
        $product = Product::join('sans', 'products.id', '=', 'sans.product_id')
            ->where('products.id', $id)
            ->where('sans.status', 'Active')
            ->select('products.*')
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found or does not have active Sans'], 404);
        }

        $comments = Comment::where('product_id', $id)
            ->where('status', 'Active')
            ->get();

        $MainImage = $product->image_main_id;
        $image_ids = $product->image_id;
        $images = [];

        if ($image_ids) {
            foreach ($image_ids as $image_id) {
                $image = Image::find($image_id);
                if ($image) {
                    $images[] = $image->getMedia('*');
                }
            }
        }

        if ($MainImage) {
            $image_Main = Image::find($MainImage);
            $Main_image = $image_Main->getMedia('*');
        }

        $video_id = $product->video_id;

        if ($video_id) {
            $video = Video::find($video_id);
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



    public function List()
    {
        $productsWithImages = Product::join('sans', 'products.id', '=', 'sans.product_id')
            ->where('sans.status', 'Active')
            ->select('products.id', 'products.title', 'products.image_main_id')
            ->distinct()
            ->get();

        foreach ($productsWithImages as $product) {
            $MainImage = $product->image_main_id;
            $Main_image = [];

            if ($MainImage) {
                $image_Main = Image::find($MainImage);

                if ($image_Main) {
                    $Main_image = $image_Main->getMedia('*');
                }
            }

            $product->images = $Main_image;
            unset($product->image_main_id);
        }

        return response()->json($productsWithImages);
    }




    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $MainImage = $product->image_main_id;
        $image_ids = $product->image_id;
        $images = [];

        if ($image_ids) {
            foreach ($image_ids as $image_id) {
                $image = Image::find($image_id);
                if ($image) {
                    $image->update(['Status' => 'Inactive']);
                }
            }
        }

        if ($MainImage){
            $image_Main = Image::find($MainImage);
            $image_Main->update(['Status' => 'Inactive']);

        }
        $video_id = $product->video_id;
        if ($video_id){
            $video = video::find($video_id);
            $video->update(['Status' => 'Inactive']);
        }

        $product->sans()->delete();
        $product->extraditions()->delete();

        $product->delete();

        return response()->json(['message' => 'Product Deleted']);
    }
}
