<?php

namespace App\Services\Ventas\DTOs;

class VentaResponse
{
    public function __construct(
        public bool $success,
        public ?string $message = null,
        public mixed $data = null,
        public array $errors = [],
        public ?string $type = 'info' // info, success, warning, error
    ) {}

    public static function success(mixed $data = null, string $message = null, string $type = 'success'): self
    {
        return new self(
            success: true,
            message: $message,
            data: $data,
            type: $type
        );
    }

    public static function error(string $message, array $errors = [], mixed $data = null): self
    {
        return new self(
            success: false,
            message: $message,
            data: $data,
            errors: $errors,
            type: 'error'
        );
    }

    public static function warning(string $message, mixed $data = null): self
    {
        return new self(
            success: false,
            message: $message,
            data: $data,
            type: 'warning'
        );
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
            'type' => $this->type
        ];
    }
}
