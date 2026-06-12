<?php

namespace App\Notifications;

use Domain\Properties\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyInquiry extends Notification
{
    use Queueable;

    public function __construct(
        public Property $property,
        public array $data,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Inquiry: {$this->property->name}")
            ->greeting('New Property Inquiry')
            ->line("Property: {$this->property->name}")
            ->line("Address: {$this->property->address}, {$this->property->city}, {$this->property->state}")
            ->line("From: {$this->data['name']} ({$this->data['email']})")
            ->line("Message: {$this->data['message']}")
            ->action('View Property', url("/admin/properties/{$this->property->id}"));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'property_id' => $this->property->id,
            'name' => $this->data['name'],
            'email' => $this->data['email'],
        ];
    }
}
