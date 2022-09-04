<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Agency;
use App\Models\EmailVerify;
use Illuminate\Support\Facades\Hash;

class AgencyWelcomeEmail extends Notification
{
    use Queueable;

    public $agency;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Agency $agency)
    {
        $this->agency = $agency;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = new EmailVerify;
        $email->email = $this->agency->email;
        $email->token = Hash::make($this->agency->email);
        $email->save();

        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/api/agency/email/verify/'.$email->token))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
