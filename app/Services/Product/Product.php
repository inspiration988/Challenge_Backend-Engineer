<?php

namespace App\Services\Product;

use Exception;

/**
 * Class Product
 *
 * @package App\Services\Product
 * @property string $sku
 * @property string $name
 * @property string $category
 * @property float $price
 * @method   Price getPrice()
 */
class Product
{

    /**
     * Product constructor.
     *
     * @param array $properties
     */
    public function __construct(protected array $properties = [])
    {
        if(isset($this->properties['price']) && is_numeric($this->properties['price'])){
            $this->properties['price'] = new Price($this->properties['price']);
        }
    }

    /**
     * Magic method to handle undefined method calls.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call(string $name, array $arguments)
    {
        if(str_starts_with($name, 'get')){
            $property_name = lcfirst(substr($name, 3));
            if(in_array( $property_name, array_keys($this->properties))){
                return $this->properties[$property_name];
            }
        }
        throw  new Exception('Call to undefined method '.self::class."::".$name );
    }

    /**
     * Magic method to handle undefined property calls.
     *
     * @param string $property_name
     * @return mixed
     * @throws Exception
     */
    public function __get(string $property_name)
    {
        if(in_array( $property_name, array_keys($this->properties))){
            return $this->properties[$property_name];
        }
        throw  new Exception('undefined property '.self::class."::".$property_name );
    }

    public function getSku(){
        return $this->properties['sku']??null;
    }
    /**
     * Convert the product to an array.
     *
     * @return array
     * @throws Exception
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->properties as $key => $property){
            if(is_object($property)){
                if (method_exists($property, 'toArray')){
                    $result[$key] = $property->toArray();
                }else{
                    throw new Exception('dont exists "toArray" method in '.get_class($property));
                }
            }else{
                $result[$key] = $property;
            }
        }
        return $result;
    }
}
