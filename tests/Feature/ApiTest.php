<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    public function test_filter_by_category(){
        $selectedCategory = "boots";
        $response = $this->get('/products?category='.$selectedCategory);
        $data = json_decode($response->getContent(), true);
        $data  = array_map(function($product){
            return $product['category'];
        }, $data['products']);

        $data = array_unique($data);
        $this->assertEqualsCanonicalizing(
            $data,
            [
                $selectedCategory
            ]
        );

    }


    public function testReturnFiveResult(){
        $response = $this->get('/products');
        $data = json_decode($response->getContent(), true);
        $this->assertLessThan(6,count($data['products']) );
    }
}
