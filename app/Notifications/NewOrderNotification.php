<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
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
        return [
            'order_id'    => $this->order->id,
            'title'       => 'طلب جديد 🛒',
            'message'     => 'قام العميل ' . ($this->order->customer->name ?? 'عميل') . ' بطلب: ' . $this->order->product_name,
            'total_price' => $this->order->total_price,
            'url'         => route('vendor.orders'),
        ];
    }
}