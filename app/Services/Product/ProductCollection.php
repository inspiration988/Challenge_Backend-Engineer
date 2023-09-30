<?php

namespace App\Services\Product;

use App\Services\Product\Discount\DiscountManager;
use Illuminate\Support\Collection;
use Exception;
/**
 * Class ProductCollection
 *
 * @package App\Services\Product
 */
class ProductCollection extends Collection
{
    /**
     * @var DiscountManager|null
     */
    protected?DiscountManager $_discountManager = null;

    /**
     * Set the discount manager for the collection.
     *
     * @param DiscountManager $_discountManager
     * @return static
     */
    public function setDiscountManager(DiscountManager $_discountManager): static
    {
        $this->_discountManager = $_discountManager;
        return $this;
    }

    /**
     * Apply a discount to each product in the collection.
     *
     * @param DiscountManager $discountManager
     * @return ProductCollection
     */
    public function applyDiscount(DiscountManager $discountManager): ProductCollection
    {
        return $this->map(function($product) use ($discountManager){
            $productArray = $product->toArray();
            $discount = $discountManager?->Calculate($product);

            $productArray['price']['final']  = ($discount == null)
                ?$productArray['price']['original']
                :(($productArray['price']['original']/100)*$discount);

            $productArray['price']['discount_percentage']  = ($discount == null)?null:$discount."%";
            return $productArray;

        });
    }

    /**
     * Sort the collection by a given property or callback function.
     *
     * @param string|callable $callback
     * @param int $options
     * @param bool $descending
     * @return ProductCollection
     */
    public function sortBy($callback, $options = SORT_REGULAR, $descending = false): ProductCollection
    {
        if(is_string($callback)){

            $sorted = $this->items;
            usort($sorted, function ($a, $b) use ($callback, $options, $descending) {
                return call_user_func([$this, '_sort'.$callback], $a, $b);
            });
            return new static($sorted);

        }else{
            return parent::sortBy($callback, $options, $descending);
        }
    }

    /**
     * Magic method to handle undefined sort method calls.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $parameters)
    {
        if(str_starts_with($method, '_sort')){
            $property_name = lcfirst(substr($method, 5));
            return $this->_sort($property_name, $parameters[0], $parameters[1]);
        }
        throw  new Exception('Call to undefined method '.self::class."::".$method );
    }

    protected function _sort($callback, $a, $b){
        if($callback == 'price') {
            return $a->getPrice()->getAmount() > $b->getPrice()->getAmount();
        }
        $methodName = "get".ucfirst($callback);

        if(method_exists($a, $methodName)){
            return $a->$methodName() > $b->$methodName();
        }
    }

    /**
     * Filter the collection to only include products in a given category.
     *
     * @param string $categoryName
     * @return ProductCollection
     */
    public function categoryIs(string $categoryName): ProductCollection
    {
        return $this->filter(function ($product) use ($categoryName){
            return $categoryName == $product->getCategory();
        });
    }

    /**
     * Filter the collection to only include products with a price less than a given amount.
     *
     * @param float $price
     * @return ProductCollection
     */
    public function priceLessThan(float $price): ProductCollection
    {
        return $this->filter(function ($product) use ($price){
            return $price >= $product->getPrice()->getAmount();
        });
    }
}
