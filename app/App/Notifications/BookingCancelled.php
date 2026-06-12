<?php

namespace App\Notifications;

use Domain\Bookings\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelled extends Notification
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
            ->subject("Booking Cancelled — {$this->booking->property->name}")
            ->greeting('Hello!')
            ->line("Your booking at {$this->booking->property->name} has been cancelled.")
            ->line("Original Check-In: {$this->booking->check_in->format('M d, Y')}")
            ->line("Original Check-Out: {$this->booking->check_out->format('M d, Y')}")
            ->line('If you have any questions, please contact support.')
            ->action('View Booking', url("/admin/bookings/{$this->booking->id}"))
            ->line('Thank you for using RentalFlow!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
        ];
    }
}
