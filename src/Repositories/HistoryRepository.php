<?php

namespace App\Repositories;

use App\Storage\FileStorage;

class HistoryRepository
{
    private FileStorage $storage;

    public function __construct(FileStorage $storage)
    {
        $this->storage = $storage;
    }

    public function save(
        string $type,
        string $isbn,
        string $memberId
    ): void {
        $history = $this->storage->load();

        $history[] = [
            'type' => $type,
            'isbn' => $isbn,
            'memberId' => $memberId,
            'date' => date('Y-m-d H:i:s')
        ];

        $this->storage->save($history);
    }

    public function findByMember(string $memberId): array
    {
        $history = $this->storage->load();
        $results = [];

        for ($i = 0; $i < count($history); $i++) {
            if ($history[$i]['memberId'] === $memberId) {
                $results[] = $history[$i];
            }
        }

        return $results;
    }

    public function findAll(): array
    {
        return $this->storage->load();
    }
}
