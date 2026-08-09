<?php

namespace App\Exceptions\Activation;

final class ActivationInviteExpiredException extends ActivationException
{
    public function __construct()
    {
        parent::__construct('activation.invite_expired');
    }
}
