<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Spm;

class SpmStatusUpdated extends Notification
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
            'status' => $this->spm->status,
            'message' => 'Dokumen SPM ' . $this->spm->nomor_spm . ' telah ' . $this->spm->status
        ];
    }
}
