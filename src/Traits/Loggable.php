<?php

namespace App\Traits;

trait Loggable
{
    public function log(string $message): void
    {
        $date = date('Y-m-d H:i:s');

        $logMessage = "[$date] $message" . PHP_EOL;

        file_put_contents(
            'data/library.log',
            $logMessage,
            FILE_APPEND
        );
    }
}
