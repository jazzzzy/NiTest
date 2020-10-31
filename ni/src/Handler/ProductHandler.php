<?php

namespace App\Handler;

use App\Entity\Product;
use App\Exception\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    public function __construct(EntityManagerInterface $entityManager, NormalizerInterface $normalizer)
    {
        $this->entityManager = $entityManager;
        $this->normalizer = $normalizer;
    }

    /**
     * @return array
     * @throws NotFoundException
     */
    public function getAllProducts(): array
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        if (empty($products)) {
            throw new NotFoundException('Something is wrong. No products found');
        }

        return $this->normalizer->normalize($products, 'array', ['groups' => ['product']]);

    }
}