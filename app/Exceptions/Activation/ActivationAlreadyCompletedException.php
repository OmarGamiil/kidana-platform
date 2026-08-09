<?php

namespace App\Exceptions\Activation;

final class ActivationAlreadyCompletedException extends ActivationException
{
    public function __construct()
    {
        parent::__construct('activation.already_completed');
    }
}
