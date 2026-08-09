<?php

namespace App\Exceptions\Activation;

final class ActivationInviteAlreadyUsedException extends ActivationException
{
    public function __construct()
    {
        parent::__construct('activation.invite_already_used');
    }
}
