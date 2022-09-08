<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\CartRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Models\Cart;
use App\Repository\Interface\User\ProductRepositoryInterface;
use App\Exceptions\ErrorException;

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






    public function getCart($category_id)
    {
        if (!is_numeric($category_id)) throw new ErrorException("Invald category ID: ");

        try{

            $response = $this->service->getCart($category_id);

            return $this->success("User Cart", $response, 200);

        }catch(\Exception $ex){
            throw new ErrorException($ex);
        }
    }





    public function removeSingleProduct(int $id)
    {
        if (!is_numeric($id)) throw new ErrorException("Invald ID");


            $response = $this->service->removeSingleProduct($id);

            return $this->success("Removed Product from Cart", $response, 200);
    }





    public function removeAll(int $category_id)
    {
        if (!is_numeric($category_id)) throw new ErrorException("Invald category ID");

        $response = $this->service->removeAll($category_id);

        return $this->success("Removed Products from Cart", $response, 200);

    }



    //use vendor address and user delivery address to calculate delivery fee
    //price will be calculated by the amount of kilometers (fixed amount for the closest and
    //farthest based on the kilometer difference between the vendor address and user address)

    //example : 300 is the fixed delivery price, but when the km difference exceeds a certain amount, it is increased to 800,

    public function setDeliveryFee(Request $request)
    {

            $validated = $request->validate([
                "logitude" => "required|string",
                "latitude" => "required|string",
                "vendor_id" => "required|exists:vendors,id",
                "cart_id" => "required|exists:carts,id"
            ]);

            $response = $this->service->setDeliveryFee($validated);

            return $this->success("Delivery fee set", $response, 200);

    }


        public function payForOrder(Request $request)
        {
            try{
            $validated = $request->validate([
                'cart.*.id' => ['required', 'numeric', 'exists:carts,id'],
                'delivery_address' => ['required', 'string'],
                'delivery_longitude' => ['required', 'string'],
                'delivery_latitude' => ['required', 'string'],
                'delivery_type' => ['required', 'in:delivery,pickup'],
                'payment_from' => ['required', 'in:wallet,card'],
                'delivery_instruction' => ['string', 'nullable'],
            ]);

            if ($request['payment_from'] == 'wallet'){

                $response = $this->service->payForOrderWallet($validated);

                return $this->success("Payment made", $response, 200);

            }else {

                $response = $this->service->payForOrder($validated);

                return $this->success("Payment details", $response, 200);

            }
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }


    }

    public function verifyPayment(Request $request)
    {
        try{
            $validated = $request->validate([
                'txref' => ['required', 'string'],
                'cart.*.id' => ['required', 'numeric', 'exists:carts,id'],
                'delivery_address' => ['required', 'string'],
                'delivery_longitude' => ['required', 'string'],
                'delivery_latitude' => ['required', 'string'],
                'delivery_type' => ['required', 'in:delivery,pickup'],
                'payment_from' => ['required', 'in:card'],
                'delivery_instruction' => ['string', 'nullable'],
            ]);

            $response = $this->service->verifyPayment($validated);

            return $this->success("Payment details", $response, 200);
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }



    public function incrementQuantity(int $id)
    {
        if (!is_numeric($id)) throw new ErrorException("Invald ID: ");

        $response = $this->service->incrementQuantity($id);

        return $this->success("Increased quantity Products from Cart", $response, 200);

    }





    public function decrementQuantity(int $id)
    {

        if (!is_numeric($id)) throw new ErrorException("Invald ID: ");

        $response = $this->service->decrementQuantity($id);

        return $this->success("Reduced quantity Products from Cart", $response, 200);

    }


    public function getOrders()
    {
        $response = $this->service->getOrders();

        return $this->success("Orders", $response, 200);

    }


    public function getSingleOrder(int $id)
    {
        if (!is_numeric($id)) throw new ErrorException("Invald ID: ");

        $response = $this->service->getSingleOrder($id);

        return $this->success("Orders", $response, 200);

    }

    //buy - move to order, payment method (paystack/wallet)

}
