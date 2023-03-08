<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Vendor;
use App\Models\EmailVerify;
use Illuminate\Support\Facades\Hash;

class VendorWelcomeEmail extends Notification
{
    use Queueable;

    public $vendor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
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
        $email->email = $this->vendor->email;
        $email->token = Hash::make($this->vendor->email);
        $email->save();

        return (new MailMessage)
                    ->line('Hi '.$this->vendor->name.',')
                    ->line('Welcome to Duka! We\'re excited that you\'re about to take this journey with us. Thank you for signing up.')
                    ->line('Click on the button below to verify yout email address.')
                    ->action('Notification Action', url('/api/vendor/email/verify/'.$email->token))
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
