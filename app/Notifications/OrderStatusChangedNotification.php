<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $statusLabels = [
            'pending'    => 'قيد الانتظار',
            'processing' => 'قيد التجهيز',
            'shipped'    => 'تم الشحن 🚚',
            'delivered'  => 'تم التوصيل بنجاح ✅',
            'cancelled'  => 'ملغى ❌',
        ];

        $statusText = $statusLabels[$this->order->status] ?? $this->order->status;

        return [
            'order_id' => $this->order->id,
            'title'    => 'تحديث حالة الطلب 📦',
            'message'  => 'تم تحديث حالة طلبك (' . $this->order->product_name . ') إلى: ' . $statusText,
            'url'      => route('customer.orders'),
        ];
    }
}