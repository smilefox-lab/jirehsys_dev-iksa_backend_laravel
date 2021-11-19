<?php

namespace Botble\ACL\Http\Controllers\Api\Auth;

use Botble\Base\Http\Controllers\BaseController;
use Botble\ACL\Traits\ResetsPasswords;

class ResetPasswordController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
}
