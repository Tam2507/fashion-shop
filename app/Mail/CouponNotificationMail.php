<?php

namespace App\Mail;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CouponNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $coupon;

    public function __construct(User $user, Coupon $coupon)
    {
        $this->user = $user;
        $this->coupon = $coupon;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎁 Mã Giảm Giá Mới Dành Cho Bạn!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.coupon-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
