<?php

namespace Tests\Filter;

use App\Entity\Product;
use App\Entity\User;
use App\Exception\ApiException;
use App\Exception\NotFoundException;
use App\Handler\UserProductHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @covers App\Handler\UserProductHandler
 */
final class UserProductHandlerTest extends TestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @var UserProductHandler
     */
    protected $productHandler;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    protected function setup(): void
    {
        /** @var NormalizerInterface normalizer */
        $this->normalizer = $this->createMock(NormalizerInterface::class);

        /** @var EntityManagerInterface entityManager */
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        /** @var ObjectRepository repository */
        $this->repository = $this->createMock(ObjectRepository::class);

        $this->productHandler = new UserProductHandler(
            $this->entityManager,
            $this->normalizer
        );
    }

    public function testGetUserProducts()
    {
        $product = new Product();
        $product->setId(1)->setName('fake_product_name')->setSku('fake_sku');
        $products = new ArrayCollection([$product]);

        $expected = [['id' => 1, 'sku' => 'fake_sku', 'name' => 'fake_product_name']];
        $user = new User();
        $user->addProduct($product);

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($products)
            ->willReturn($expected);

        $products = $this->productHandler->getUserProducts($user);

        self::assertEquals($expected, $products);
    }

    public function testGetUserProductsWorksIfNoProductsReturned()
    {
        $products = new ArrayCollection([]);

        $expected = [['id' => 1, 'sku' => 'fake_sku', 'name' => 'fake_product_name']];
        $user = new User();

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($products)
            ->willReturn($expected);

        $products = $this->productHandler->getUserProducts($user);

        self::assertEquals($expected, $products);
    }

    public function testDeleteUserProduct()
    {
        $expected = [['id' => 2, 'sku' => 'fake_sku2', 'name' => 'fake_product_name2']];

        $product1 = new Product();
        $product1->setId(1)->setName('fake_product_name')->setSku('fake_sku');
        $product2 = new Product();
        $product2->setId(2)->setName('fake_product_name2')->setSku('fake_sku2');

        $user = new User();
        $user->addProduct($product1);
        $user->addProduct($product2);

        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->repository);
        $this->repository->expects($this->once())->method('find')->willReturn($product1);
        $this->entityManager->expects($this->once())->method('flush');

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->willReturn($expected);

        $products = $this->productHandler->deleteUserProduct($user, 1);

        self::assertEquals($expected, $products);
    }

    public function testDeleteUserProductThrowsExceptionIfUserHasNoSuchProductToDelete()
    {
        $product1 = new Product();
        $product1->setId(1)->setName('fake_product_name')->setSku('fake_sku');
        $product2 = new Product();
        $product2->setId(2)->setName('fake_product_name2')->setSku('fake_sku2');
        $user = new User();
        $user->addProduct($product1);

        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->repository);
        $this->repository->expects($this->once())->method('find')->willReturn($product2);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The user has no such product');

        $this->productHandler->deleteUserProduct($user, 2);
    }


    public function testAddUserProduct()
    {
        $expected = [
            ['id' => 1, 'sku' => 'fake_sku1', 'name' => 'fake_product_name1'],
            ['id' => 2, 'sku' => 'fake_sku2', 'name' => 'fake_product_name2'],
        ];

        $product1 = new Product();
        $product1->setId(1)->setName('fake_product_name')->setSku('fake_sku');
        $product2 = new Product();
        $product2->setId(2)->setName('fake_product_name2')->setSku('fake_sku2');

        $user = new User();
        $user->addProduct($product1);

        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->repository);
        $this->repository->expects($this->once())->method('find')->willReturn($product2);
        $this->entityManager->expects($this->once())->method('flush');

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->willReturn($expected);

        $products = $this->productHandler->addUserProduct($user, 2);

        self::assertEquals($expected, $products);
    }

    public function testAddUserProductThrowsExceptionIfUserHasSuchProduct()
    {
        $product1 = new Product();
        $product1->setId(1)->setName('fake_product_name')->setSku('fake_sku');
        $product2 = new Product();
        $product2->setId(2)->setName('fake_product_name2')->setSku('fake_sku2');
        $user = new User();
        $user->addProduct($product1);
        $user->addProduct($product2);

        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->repository);
        $this->repository->expects($this->once())->method('find')->willReturn($product2);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('The user has already this product');

        $this->productHandler->addUserProduct($user, 2);
    }
}
