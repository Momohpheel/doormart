<?php
namespace App\Repository\Vendor;

use App\Models\Vendor;
use App\Models\Product;
use App\Repository\Interface\Vendor\ProductRepositoryInterface;
use App\Trait\Response;
use App\Trait\Token;
use Illuminate\Http\Request;
use App\Notifications\VendorForgotPasswordEmail;



class ProductRepository implements ProductRepositoryInterface
{

    use Response, Token;

    public function addProduct(array $request)
    {
        $product = new Product;
        $product->name = $request['name'];
        $product->description = $request['description'];
        $product->image_1 = $request['image1'];
        $product->image_2 = $request['image2'];
        $product->image_3 = $request['image3'];
        $product->image_4 = $request['image4'];
        $product->price = $request['price'];
        $product->product_category_id = $request['product_category_id'];
        $product->vendor_id = auth()->user()->id;
        $product->save();


        return $product;

        //name, description, images(4), price, category, time of preparation
    }

    public function updateProduct(array $request, int $id)
    {
        $user = auth()->user()->id;

        $product = Product::where('id', $id)->where('vendor_id', $user)->first();
        $product->name = isset($request['name']) ? $request['name'] : $product->name;
        $product->description = isset($request['description']) ? $request['description'] : $product->description;
        $product->image_1 = isset($request['image1']) ? $request['image1'] : $product->image_1;
        $product->image_2 = isset($request['image2']) ? $request['image2'] : $product->image_2;
        $product->image_3 = isset($request['image3']) ? $request['image3'] : $product->image_3;
        $product->image_4 = isset($request['image4']) ? $request['image4'] : $product->image_4;
        $product->price = isset($request['price']) ? $request['price'] : $product->price;
        $product->product_category_id = isset($request['product_category_id']) ? $request['product_category_id'] : $product->product_category_id;

        $product->save();

        return $product;

    }

    public function removeProduct(int $id)
    {
        $user = auth()->user()->id;

        $products = Product::with('productCategory')->where('id', $id)->where('vendor_id', $user)->first();

        $products->delete();

        return true;
    }

    public function getProducts()
    {
        $user = auth()->user()->id;

        $products = Product::with('productCategory')->where('vendor_id', $user)->get();

        return $products;
    }

    public function getSingleProduct(int $id)
    {
        $user = auth()->user()->id;

        $products = Product::with('productCategory')->where('id', $id)->where('vendor_id', $user)->first();

        return $products;
    }

}

