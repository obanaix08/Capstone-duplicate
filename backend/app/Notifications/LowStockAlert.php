<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $itemType, public string $name, public float $stock, public float $threshold)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Stock Alert: '.$this->name)
            ->line(ucfirst($this->itemType).' "'.$this->name.'" is low on stock.')
            ->line('Current stock: '.$this->stock.' | Threshold: '.$this->threshold)
            ->action('Open Inventory', url('/inventory'));
    }
}

