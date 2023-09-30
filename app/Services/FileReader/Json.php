<?php

namespace App\Services\FileReader;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;


class Json implements FileReaderServiceInterface
{
    public function __construct(protected $fileName)
    {
    }


    public function get() : Collection
    {
        $products = collect([]);
        if (Storage::disk('local')->exists($this->fileName)) {
            $products = collect(json_decode(Storage::disk('local')->get($this->fileName), true));
        }
        return $products;
    }
}
