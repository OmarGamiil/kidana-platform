<?php

namespace App\Exceptions\Activation;

use RuntimeException;

abstract class ActivationException extends RuntimeException
{
    public function __construct(protected readonly string $translationKey)
    {
        parent::__construct($translationKey);
    }

    public function translationKey(): string
    {
        return $this->translationKey;
    }
}
