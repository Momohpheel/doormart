<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\CartRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Model\Cart;
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
            // return $this->error($e->getMessage());
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


    public function orderProduct()
    {

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


    //buy - move to order, payment method (paystack/wallet)

}
