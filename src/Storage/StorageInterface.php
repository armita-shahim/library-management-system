<?php

namespace App\Storage;

interface StorageInterface
{
    public function save(array $data): void;
    public function load(): array;
}
