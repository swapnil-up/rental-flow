<?php

namespace App\Notifications;

use Domain\Bookings\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Booking Confirmed — {$this->booking->property->name}")
            ->greeting('Hello!')
            ->line("Your booking at {$this->booking->property->name} has been confirmed.")
            ->line("Check-In: {$this->booking->check_in->format('M d, Y')}")
            ->line("Check-Out: {$this->booking->check_out->format('M d, Y')}")
            ->action('View Booking', url("/admin/bookings/{$this->booking->id}"))
            ->line('Thank you for choosing RentalFlow!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
        ];
    }
}
