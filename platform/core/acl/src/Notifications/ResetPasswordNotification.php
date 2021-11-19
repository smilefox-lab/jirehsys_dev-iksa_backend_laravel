<?php

namespace Botble\ACL\Notifications;

use App;
use Botble\ACL\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Throwable;

class ResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;
    protected $user;

    /**
     * Create a notification instance.
     *
     * @param string $token
     */
    public function __construct($token, User $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     * @throws Throwable
     */
    public function toMail($notifiable)
    {
        if (!$this->user->inRole('admin') && !$this->user->isSuperUser()) {
            $link = App::environment('production') ? env('APP_FRONTEND_PRODUCTION') : env('APP_FRONTEND_LOCAL');
            $link .= $this->token;

            return (new MailMessage)
                ->view('core/acl::emails.api.reminder', [
                    'link' => $link
                ]);
        }

        return (new MailMessage)
        ->view('core/acl::emails.reminder', ['link' => route('access.password.reset', ['token' => $this->token])]);
    }
}
