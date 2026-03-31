<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Exception;

abstract class ApiException extends \RuntimeException
{
    public function __construct(
        string $message,
        private readonly int $statusCode,
    ) {
        parent::__construct($message);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }
}
