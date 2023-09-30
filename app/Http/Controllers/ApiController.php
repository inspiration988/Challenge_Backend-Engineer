<?php

namespace App\Http\Controllers;

use App\Services\FileReader\FileReader;
use App\Services\FileReader\Json;
use App\Services\Product\Discount\DiscountManager;
use App\Services\Product\Discount\DiscountRule\DiscountByCategory;
use App\Services\Product\Discount\DiscountRule\DiscountBySKU;
use App\Services\Product\Product;
use App\Services\Product\ProductCollection;
use Illuminate\Http\Request;
use \Illuminate\Support\Collection;
class ApiController extends Controller
{

    protected $db;

    private function getDbFile(): Collection
    {
        return  (new FileReader(env('DATABASE_FILE')))->get();
    }

    private function getProductCollection(): ProductCollection
    {
        $db = $this->getDbFile();

        $collection = new ProductCollection();

        if(isset($db['products'])){
            foreach ($db['products'] as $productProperty){
                $collection->push(new Product($productProperty));
            }
        }

        return $collection;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {


        $productCollection = $this->getProductCollection();


        /**
         * filter by product category
         * ?category=x
         */
        if ($request->filled('category')) {
            $productCollection = $productCollection->categoryIs($request->input('category'));
        }

        /**
         * filter by price less than x
         * ?priceLessThan=x
         */
        if ($request->filled('priceLessThan')) {
            $productCollection = $productCollection->priceLessThan(floatval($request->input('priceLessThan')));
        }

        /**
         * sorting product with sort parameter
         * ?sort=price|category|name....
         *
         */
        if ($request->filled('sort')) {
            $productCollection = $productCollection->sortBy($request->input('sort'));
        }


        /**
         * pagination
         * page as a query string parameter
         */
        $page = (int) $request->input('page',0);
        //$page = ($page != 0 ) ? $page-1 : 0;
        $perPage = 5;

        // Define discount manager to apply discount rules
        $discountManager = (new DiscountManager())
            ->addRule(DiscountByCategory::class)
            ->addRule(DiscountBySKU::class);


        return response()->json([
            'status' => true,
            'products' => $productCollection
                ->applyDiscount($discountManager)
                ->forPage($page, $perPage)
            ,
            'total' => $productCollection->count(),
            'page' => $page+1,
            'total_page' => ceil($productCollection->count() / $perPage)
        ]);
    }

}
