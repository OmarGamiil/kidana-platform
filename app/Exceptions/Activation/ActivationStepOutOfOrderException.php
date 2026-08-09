<?php

namespace App\Exceptions\Activation;

final class ActivationStepOutOfOrderException extends ActivationException
{
    public function __construct()
    {
        parent::__construct('activation.step_out_of_order');
    }
}
