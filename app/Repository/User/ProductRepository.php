<?php
namespace App\Repository\User;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Cart;
use App\Repository\Interface\User\ProductRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;


class ProductRepository implements ProductRepositoryInterface
{

    use Response;

    public function addToCart(array $request)
    {
        $product = Product::where('id', $request['product_id'])->first();

        $cart = new Cart;
        $cart->product_id = $request['product_id'];
        $cart->vendor_id = $request['vendor_id'];
        $cart->category_id = $request['category_id'];
        $cart->user_id = auth()->user()->id;
        $cart->quantity = $request['quantity'];
        $cart->price = (int)$product->price * (int)$request['quantity'];
        $cart->save();

        return $cart;
    }

    public function getCart()
    {
        $cart = Cart::with(['products', 'vendors', 'categories'])->where('user_id', auth()->user()->id)->get();

        return $cart;
    }

    public function removeSingleProduct()
    {

    }


    public function removeAll()
    {

    }


    public function orderProduct()
    {

    }
}

