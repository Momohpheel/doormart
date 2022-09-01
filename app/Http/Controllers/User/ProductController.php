<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\CartRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Model\Cart;
use App\Repository\Interface\User\ProductRepositoryInterface;

class ProductController extends Controller
{
    use Response;

    public $service;

    public function __construct(ProductRepositoryInterface $service)
    {
        $this->service = $service;
    }

    public function addToCart(CartRequest $request)
    {

        $validated = $request->validated();

        $response = $this->service->addToCart($validated);

        return $this->success("Product added to Cart", $response, 200);

    }

    public function getCart()
    {
        $response = $this->service->getCart();

        return $this->success("User Cart", $response, 200);

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
    //add to cart, bag (category_id, vendor_id, user_id, product_id)

    //get bag - (category_id, user_id)

    //remove from bag

    //buy - move to order, payment method (paystack/wallet)

}
