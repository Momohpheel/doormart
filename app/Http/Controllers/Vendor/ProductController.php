<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repository\Interface\Vendor\ProductRepositoryInterface;

class ProductController extends Controller
{
    use Response;

    public $service;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->service = $repository;
    }

    public function addProduct(ProductRequest $request)
    {
        $validated = $request->validated();

        $validated['image1'] = cloudinary()->upload($request->file('image_1')->getRealPath())->getSecurePath();
        $validated['image2'] = cloudinary()->upload($request->file('image_2')->getRealPath())->getSecurePath();
        $validated['image3'] = cloudinary()->upload($request->file('image_3')->getRealPath())->getSecurePath();
        $validated['image4'] = cloudinary()->upload($request->file('image_4')->getRealPath())->getSecurePath();


        $response = $this->service->addProduct($validated);

        return $this->success("Product added", $response, 200);
    }

    public function updateProduct(UpdateProductRequest $request, int $id)
    {

        $validated = $request->validated();


        $response = $this->service->updateProduct($validated, $id);

        return $this->success("Product updated", $response, 200);
    }

    public function removeProduct(int $id)
    {
        $response = $this->service->removeProduct($id);

        return $this->success("Product removed", $response, 200);
    }

    public function getProducts()
    {
        $response = $this->service->getProducts();

        return $this->success("Your Products", $response, 200);
    }

    public function getSingleProduct(int $id)
    {

        $response = $this->service->getSingleProduct($id);

        return $this->success("Your Products", $response, 200);
    }

}
