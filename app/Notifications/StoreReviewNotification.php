<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StoreReviewNotification extends Notification
{
    use Queueable;

    public $clientName;
    public $rating;

    public function __construct($clientName, $rating)
    {
        $this->clientName = $clientName;
        $this->rating = $rating;
    }

    public function via($notifiable): array
    {
        return ['database']; // تخزين الإشعار في قاعدة البيانات
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'تقييم جديد للمتجر ⭐',
            'message' => "قام العميل {$this->clientName} بتقييم متجرك بـ {$this->rating} نجوم.",
            'url' => route('vendor.dashboard'),
        ];
    }
}