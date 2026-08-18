<?php

namespace App\Repositories;

use App\Storage\FileStorage;

abstract class AbstractRepository
{

    protected FileStorage $storage;

    public function __construct(FileStorage $storage)
    {
        $this->storage = $storage;
    }

    abstract public function findAll(): array;
}
