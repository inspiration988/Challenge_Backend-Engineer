<?php
namespace App\Services\Product;

use JetBrains\PhpStorm\ArrayShape;

class Price{

    protected static string $default_currency = 'EUR';

    const CURRENCY_EUR = 'EUR';
    const CURRENCY_USD = 'USD';

    public function __construct(
        protected float $_amount,
        protected string $_currency = self::CURRENCY_EUR
        )
    {


    }

    public function getAmount(): int
    {
        /**
         * @todo : change price currency if needed
         * Sample :
         *      if(self::$default_currency != $this->getCurrency()){
         *          // todo ......
         *      }
         */

        /**
         * @todo : convert 100.00€ to 10000
         */

        /**
         * @todo : Apply other policy
         */

        return $this->_amount;
    }

    public function getCurrency(): string
    {
        return $this->_currency;
    }

    public static function setDefaultCurrency($defaultCurrency) : void{
        self::$default_currency = $defaultCurrency;
    }


    #[ArrayShape(['currency' => "string", 'original' => "int"])]
    public function toArray() :array{
        return [
            'currency' => $this->getCurrency(),
            'original' => $this->getAmount(),
        ];
    }

}
