<?php

namespace App\Enums;

enum AuditEvent: string
{
    case UserCreated = 'user.created';
    case UserLoggedIn = 'user.logged_in';
    case UserLoggedOut = 'user.logged_out';
    case ActivationInviteIssued = 'activation.invite_issued';
    case ActivationAccountDefined = 'activation.account_defined';
    case ActivationOtpVerified = 'activation.otp_verified';
    case ActivationCompleted = 'activation.completed';
    case OfficeSeasonStatusChanged = 'office_season.status_changed';
}
