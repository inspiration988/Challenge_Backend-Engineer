<?php

namespace Tests\Unit;

use App\Services\Product\Discount\DiscountManager;
use App\Services\Product\Discount\DiscountRule\DiscountByCategory;
use App\Services\Product\Discount\DiscountRule\DiscountBySKU;
use App\Services\Product\Product;
use App\Services\Product\ProductCollection;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{

    public function testApplyDiscount()
    {
        $product = $this->_getProducts();
        $collection = new ProductCollection([new Product($product[1])]);
        $discountManager = (new DiscountManager())
            ->addRule(DiscountByCategory::class)
            ->addRule(DiscountBySKU::class);
        $result = $collection->applyDiscount($discountManager);
        $this->assertEquals(substr($result->first()['price']['discount_percentage'], -1), "%");
        $this->assertNotEquals(
            $result->first()['price']['final'],
            $result->first()['price']['original'],
        );
    }

    public function testWithoutDiscount()
    {
        $product = $this->_getProducts();
        $collection = new ProductCollection([new Product($product[0])]);
        $discountManager = (new DiscountManager())
            ->addRule(DiscountByCategory::class)
            ->addRule(DiscountBySKU::class);
        $result = $collection->applyDiscount($discountManager);
        $this->assertNull($result->first()['price']['discount_percentage']);
        $this->assertEquals(
            $result->first()['price']['final'],
            $result->first()['price']['original'],
        );
    }

    public function testDiscountByCategory()
    {
        $product = $this->_getProducts();
        $collection = new ProductCollection([new Product($product[1])]);
        $discountManager = (new DiscountManager())
            ->addRule(DiscountByCategory::class)
            ->addRule(DiscountBySKU::class);

        $result = $collection->applyDiscount($discountManager);
        $this->assertEqualsCanonicalizing(
            [
                $result->first()['category'],
                $result->first()['price']['discount_percentage']
            ]
            ,
            [
                "boots",
                "30%"

            ]);

    }

    public function testDiscountBySKU()
    {
        $product = $this->_getProducts();
        $collection = new ProductCollection([new Product($product[3])]);
        $discountManager = (new DiscountManager())
            ->addRule(DiscountByCategory::class)
            ->addRule(DiscountBySKU::class);
        $result = $collection->applyDiscount($discountManager);
        $this->assertEqualsCanonicalizing(
            [
                $result->first()['sku'],
                $result->first()['price']['discount_percentage']
            ]
            ,
            [
                "000003",
                "15%"
            ]);

    }

    public function testPriceIsInt()
    {
        $product = new Product($this->_getProducts()[1]);
        $this->assertIsInt($product->getPrice()->getAmount());

    }

    public function testBiggestDiscount()
    {
        $product = $this->_getProducts();
        $collection = new ProductCollection([new Product($product[2])]);
        $discountManager = (new DiscountManager())
            ->addRule(DiscountBySKU::class)
            ->addRule(DiscountByCategory::class);
        $result = $collection->applyDiscount($discountManager);
        $this->assertEqualsCanonicalizing(
            [
                $result->first()['sku'],
                $result->first()['category'],
                $result->first()['price']['discount_percentage']
            ]
            ,
            [
                "000003",
                "boots",
                "30%"

            ]);

    }


    private function _getProducts()
    {

        return json_decode('
                [{
                    "sku": "000001",
                    "name": "BV Lean leather ankle boots",
                    "category": "cat1",
                    "price": 89000
                },
                {
                    "sku": "000002",
                    "name": "BV Lean leather ankle boots",
                    "category": "boots",
                    "price": 99000
                },
                {
                    "sku": "000003",
                    "name": "BV Lean leather ankle boots",
                    "category": "boots",
                    "price": 99000
                },
                {
                    "sku": "000003",
                    "name": "BV Lean leather ankle boots",
                    "category": "diff",
                    "price": 99000
                }]
        ', true);
    }


}
