<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Spm;

class SpmSubmitted extends Notification
{
    use Queueable;

    protected $spm;

    /**
     * Create a new notification instance.
     */
    public function __construct(Spm $spm)
    {
        $this->spm = $spm;
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
            'spm_id' => $this->spm->id,
            'nomor_spm' => $this->spm->nomor_spm,
            'message' => 'SPM Baru: ' . $this->spm->nomor_spm . ' menunggu verifikasi.'
        ];
    }
}
