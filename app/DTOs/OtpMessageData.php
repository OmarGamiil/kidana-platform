<?php

namespace App\DTOs;

final readonly class OtpMessageData
{
    public function __construct(
        public string $recipient,
        public string $code,
        public string $purpose,
    ) {}
}
