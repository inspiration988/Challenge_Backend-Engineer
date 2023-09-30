<?php
namespace App\Services\Product\Discount\DiscountRule;

use App\Services\Product\Product;

abstract class DiscountRuleAbstract{

    public function __construct(
        protected Product $_product
    )
    {

    }

    abstract public function calculate() : float;
}
