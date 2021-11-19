<?php

use Botble\Vendor\Notifications\ConfirmEmailNotification;

return [

    /*
    |--------------------------------------------------------------------------
    | Notification
    |--------------------------------------------------------------------------
    |
    | This is the notification class that will be sent to users when they receive
    | a confirmation code.
    |
    */
    'notification' => ConfirmEmailNotification::class,

    'verify_email' => env('CMS_VENDOR_VERIFY_EMAIL', true),
];
