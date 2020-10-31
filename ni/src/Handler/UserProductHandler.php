<?php

namespace App\Handler;

use App\Entity\Product;
use App\Entity\User;
use App\Exception\ApiException;
use App\Exception\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserProductHandler
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
     * @param User $user
     *
     * @return array
     */
    public function getUserProducts(User $user): array
    {
        $products = $user->getProducts();

        return $this->normalizer->normalize($products, 'array', ['groups' => ['user_product']]);
    }

    /**
     * @param User $user
     * @param string $sku
     * @return array
     * @throws NotFoundException
     */
    public function deleteUserProduct(User $user, string $sku): array
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['sku' => $sku]);
        $products = $user->getProducts();

        if ( !$products->contains($product) ) {
            throw new NotFoundException('The user has no such product');
        }

        $products->removeElement($product);
        $this->entityManager->flush();

        return $this->normalizer->normalize($products, 'array', ['groups' => ['user_product']]);
    }

    /**
     * @param User $user
     * @param int $id
     *
     * @return array
     * @throws ApiException
     */
    public function addUserProduct(User $user, int $id): array
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        $products = $user->getProducts();

        if ( $products->contains($product) ) {
            throw new ApiException('The user has already this product');
        }

        $user->addProduct($product);
        $this->entityManager->flush();

        return $this->normalizer->normalize($products, 'array', ['groups' => ['user_product']]);
    }
}