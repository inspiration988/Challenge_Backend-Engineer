<?php

namespace App\Services\FileReader;

use Illuminate\Support\Collection;

interface FileReaderServiceInterface
{

    public function get() : Collection;
}
