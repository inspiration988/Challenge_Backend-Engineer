<?php

namespace App\Services\Product\Discount\DiscountRule;

use phpDocumentor\Reflection\Types\Mock\Foo;

class DiscountBySKU extends DiscountRuleAbstract{

    public function calculate(): float
    {
        if($this->_product->getSku() ==  "000003"){
            return 15;
        }
        return 0;
    }
}
