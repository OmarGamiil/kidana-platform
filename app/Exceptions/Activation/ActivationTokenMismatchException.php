<?php

namespace App\Exceptions\Activation;

final class ActivationTokenMismatchException extends ActivationException
{
    public function __construct()
    {
        // Deliberately the same translation key as a not-found invite —
        // a token mismatch must not leak which part of the link was wrong.
        parent::__construct('activation.invite_not_found');
    }
}
