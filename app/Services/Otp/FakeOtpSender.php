<?php

namespace App\Services\Otp;

use App\Contracts\OtpSender;
use App\DTOs\OtpMessageData;

/**
 * Bound directly in tests. Records sent messages in memory for assertions
 * instead of touching a log channel or a real provider.
 */
final class FakeOtpSender implements OtpSender
{
    /** @var list<OtpMessageData> */
    private array $sent = [];

    public function send(OtpMessageData $message): void
    {
        $this->sent[] = $message;
    }

    /**
     * @return list<OtpMessageData>
     */
    public function sent(): array
    {
        return $this->sent;
    }
}
