<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Channels\BroadcastChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database','broadcast'];

        
        $channels = ['database'];
        if($notifiable->notification_preferences['order_created']['sms']?? false){
            $channels[] = 'voyage';
        }

        if($notifiable->notification_preferences['order_created']['mail']?? false){
            $channels[] = 'mail';
        }

        if($notifiable->notification_preferences['order_created']['broadcast']?? false){
            $channels[] = 'broadcast';
        }

        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $addr = $this->order->billingAddress;
        return (new MailMessage)
                    ->subject("New Order #{$this->order->number}")
                    ->from('notification@admin.com','ABDALLAH')
                    ->greeting("Hello {$notifiable->name}")
                    ->line("New Order (#{$this->order->number}) created by {$addr->name} from {$addr->country_name} ")
                    ->action('View Order', url('/dashboard'))
                    ->line('Thank you for using our application!')
                    ;
    }

    public function toDatabase($notifiable){
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->number,
            'order_total' => $this->order->total,
            'store_name' => $this->order->store->name,
            'order_status' => $this->order->status,
            'icon' => 'fas fa-file',
            'url' => '/',
            'created_at' => $this->order->created_at->toDateTimeString(),
            'message' => "New Order (#{$this->order->number}) created by {$this->order->billingAddress->name} from {$this->order->billingAddress->country_name}.",

        ];
    }

    public function toBroadcast($notifiable)
    {
        $addr = $this->order->billingAddress;
        return new BroadcastMessage([
            'message' => "A new order (#{$this->order->number}) created by {$addr->name} from {$addr->country_name}.",
            'icon' => 'fas fa-file',
            'url' => url('/dashboard'),
            'order_id' => $this->order->id,
        ]);
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
            'order_id' => $this->order->id,
            'store_id' => $this->order->store_id,
            'order_number' => $this->order->number,

        ];
    }
}
