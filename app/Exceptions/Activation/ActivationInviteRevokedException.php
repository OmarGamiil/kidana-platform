<?php

namespace App\Exceptions\Activation;

final class ActivationInviteRevokedException extends ActivationException
{
    public function __construct()
    {
        parent::__construct('activation.invite_revoked');
    }
}
