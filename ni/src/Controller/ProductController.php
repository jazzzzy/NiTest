<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", methods={"GET"})
     */
    public function listProducts()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        /** @var SerializerInterface $serializer */
        $serializer = $this->get('serializer');
        $productsArray = $serializer->normalize($products, 'array', ['groups' => ['product']]);

        return new JsonResponse(
            $productsArray,
            200
        );
    }
}