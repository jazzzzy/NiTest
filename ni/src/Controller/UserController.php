<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", methods={"GET"})
     */
    public function showUser()
    {
        $user = $this->getUser();

        return new JsonResponse(
            ['name' => $user->getName()],
            200
        );
    }

    /**
     * @Route("/user/products", methods={"GET"})
     */
    public function showUserProducts()
    {
        /** @var User $user */
        $user = $this->getUser();

        $products = $user->getProducts();

        /** @var SerializerInterface $serializer */
        $serializer = $this->get('serializer');
        $productsArray = $serializer->normalize($products, 'array', ['groups' => ['user_product']]);

        return new JsonResponse(
            $productsArray,
            200
        );
    }

    /**
     * @Route("/user/products/{id}", methods={"DELETE"})
     */
    public function deleteUserProducts($id)
    {
        /** @var User $user */
        $user = $this->getUser();
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $products = $user->getProducts();
        $products->removeElement($product);
        $this->getDoctrine()->getManager()->flush();

        /** @var SerializerInterface $serializer */
        $serializer = $this->get('serializer');
        $productsArray = $serializer->normalize($products, 'array', ['groups' => ['user_product']]);

        return new JsonResponse(
            $productsArray,
            200
        );
    }

    /**
     * @Route("/user/products", methods={"POST"})
     */
    public function addUserProducts(Request $request)
    {
        $content = $request->getContent();
        $contentArray = json_decode($content, true);
        $product = $this->getDoctrine()->getRepository(Product::class)->find($contentArray['id']);

        /** @var User $user */
        $user = $this->getUser();
        $products = $user->getProducts();

        if ( !$products->contains($product) ) {
            $user->addProduct($product);
            $this->getDoctrine()->getManager()->flush();
        }

        /** @var SerializerInterface $serializer */
        $serializer = $this->get('serializer');
        $productsArray = $serializer->normalize($products, 'array', ['groups' => ['user_product']]);

        return new JsonResponse(
            $productsArray,
            200
        );
    }
}