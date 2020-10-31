<?php

namespace App\Controller;

use App\Handler\ProductHandler;
use App\ResponseBuilder\ErrorResponse;
use App\ResponseBuilder\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", methods={"GET"})
     */
    public function listProducts(ProductHandler $productHandler)
    {
        try {
            $products = $productHandler->getAllProducts();
        } catch (\Exception $e) {
            return new ErrorResponse($e);
        }

        return new SuccessResponse($products);

    }
}