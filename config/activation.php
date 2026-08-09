<?php

return [

    'invite_expiry_days' => env('ACTIVATION_INVITE_EXPIRY_DAYS', 7),

    'otp_length' => env('ACTIVATION_OTP_LENGTH', 6),

    'otp_expiry_minutes' => env('ACTIVATION_OTP_EXPIRY_MINUTES', 5),

    // Provisional — pending product confirmation. Derived from the "04:20" countdown
    // visually shown in the Figma design; a UI countdown is not a confirmed business rule.
    'otp_resend_cooldown_seconds' => env('ACTIVATION_OTP_RESEND_COOLDOWN_SECONDS', 260),

    'otp_max_attempts' => env('ACTIVATION_OTP_MAX_ATTEMPTS', 5),

    'current_terms_version' => env('ACTIVATION_TERMS_VERSION', '1.0'),

];
