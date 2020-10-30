<?php

namespace Tests\Filter;

use App\Entity\Product;
use App\Exception\NotFoundException;
use App\Handler\ProductHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @covers App\Handler\ProductHandler
 */
final class ProductHandlerTest extends TestCase
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
     * @var ProductHandler
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

        $this->productHandler = new ProductHandler(
            $this->entityManager,
            $this->normalizer
        );

    }

    public function testAllProducts()
    {
        $expected = [['id' => 1, 'sku' => 'fake_sku', 'name' => 'fake_product_name']];

        $product = new Product();
        $product->setId(1)->setName('fake_product_name')->setSku('fake_sku');
        $products = new ArrayCollection([$product]);

        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->repository);
        $this->repository->expects($this->once())->method('findAll')->willReturn($products);

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->willReturn($expected);

        $products = $this->productHandler->getAllProducts();

        self::assertEquals($expected, $products);
    }

    public function testNoProductsFoundException()
    {
        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($this->repository);
        $this->repository->expects($this->once())->method('findAll')->willReturn(null);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Something is wrong. No products found');

        $this->productHandler->getAllProducts();
    }

//
//
//    public function testGetReturnsSuccessResponse() {
//
//        $dataProvider = $this->prophesize(DefaultDataProvider::class);
//        $dataProvider->get(Recipe::class, 13)->shouldBeCalled()->willReturn(['asd']);
//
//        $handler = $this
//            ->getMockBuilder(HandlerAbstract::class)
//            ->setConstructorArgs([$dataProvider->reveal(), Recipe::class])
//            ->getMockForAbstractClass();
//
//        $result = $handler->get(13);
//
//        self::assertInstanceOf(SuccessResponse::class, $result);
//    }
//
//    public function testGetReturnsErrorResponse()
//    {
//        $dataProvider = $this->createMock(DefaultDataProvider::class);
//        $dataProvider->expects($this->once())
//            ->method('get')
//            ->will($this->throwException(
//                new \Exception())
//            );
//
//        $handler = $this
//            ->getMockBuilder(HandlerAbstract::class)
//            ->setConstructorArgs([$dataProvider, Recipe::class])
//            ->getMockForAbstractClass();
//
//        $result = $handler->get(13);
//
//        self::assertInstanceOf(ErrorResponse::class, $result);
//    }
}
