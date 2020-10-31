<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Handler\UserProductHandler;
use App\Parser\RequestParser;
use App\ResponseBuilder\ErrorResponse;
use App\ResponseBuilder\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserProductController extends AbstractController
{
    /**
     * @Route("/user/products", methods={"GET"})
     */
    public function listUserProducts(UserProductHandler $userProductHandler)
    {
        try {
            /** @var User $user */
            $user = $this->getUser();

            /** @var UserProductHandler $productHandler */
            $products = $userProductHandler->getUserProducts($user);
        } catch (\Exception $e) {
            return new ErrorResponse($e);
        }

        return new SuccessResponse($products);
    }

    /**
     * @Route("/user/products/{sku}", methods={"DELETE"})
     */
    public function deleteUserProduct($sku, UserProductHandler $userProductHandler)
    {
        try {
            /** @var User $user */
            $user = $this->getUser();

            /** @var UserProductHandler $productHandler */
            $products = $userProductHandler->deleteUserProduct($user, $sku);
        } catch (\Exception $e) {
            return new ErrorResponse($e);
        }

        return new SuccessResponse($products);
    }

    /**
     * @Route("/user/products", methods={"POST"})
     */
    public function addUserProduct(Request $request, UserProductHandler $userProductHandler, RequestParser $requestParser)
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            $id = $requestParser->getId($request);

            /** @var UserProductHandler $productHandler */
            $products = $userProductHandler->addUserProduct($user, $id);
        } catch (\Exception $e) {
            return new ErrorResponse($e);
        }

        return new SuccessResponse($products);
    }
}