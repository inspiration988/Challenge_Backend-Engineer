<?php

namespace App\Services\FileReader;

class FileReader
{
    public function __construct(protected string $fileName)
    {
    }

    public function get(): \Illuminate\Support\Collection
    {
        /**
         * @todo : We need to find the best class for converting a file into a collection using the getService method
         * $service = $this->getService()
         * $service->get($this->fileName)
         */
        return ( new Json($this->fileName))
            ->get();
    }

    protected function getService(){

    }
}
