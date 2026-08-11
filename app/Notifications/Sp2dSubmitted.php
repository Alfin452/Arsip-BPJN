<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Sp2d;

class Sp2dSubmitted extends Notification
{
    use Queueable;

    protected $sp2d;

    /**
     * Create a new notification instance.
     */
    public function __construct(Sp2d $sp2d)
    {
        $this->sp2d = $sp2d;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sp2d_id' => $this->sp2d->id,
            'nomor_sp2d' => $this->sp2d->nomor_sp2d,
            'message' => 'SP2D Baru: ' . $this->sp2d->nomor_sp2d . ' menunggu verifikasi.'
        ];
    }
}
