<?php

namespace App\Storage;

class FileStorage implements StorageInterface
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function save(array $data): void
    {
        $json = json_encode(
            $data,
            JSON_PRETTY_PRINT
        );

        file_put_contents($this->filePath, $json);
    }

    public function load(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);

        if ($json === false || $json === '') {
            return [];
        }

        $data = json_decode($json, true);

        if (is_array($data)) {
            return $data;
        }
        return [];
    }
}
