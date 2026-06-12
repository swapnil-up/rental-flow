<?php

namespace App\Notifications;

use Domain\Maintenance\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public MaintenanceRequest $request,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Maintenance Update — {$this->request->title}")
            ->greeting('Hello!')
            ->line("Your maintenance request \"{$this->request->title}\" has been updated.")
            ->line("Status: {$this->request->status->label()}")
            ->line("Property: {$this->request->property->name}")
            ->action('View Request', url("/admin/maintenance/{$this->request->id}"))
            ->line('Thank you for using RentalFlow!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'maintenance_request_id' => $this->request->id,
        ];
    }
}
