<?php

namespace App\Mail;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CouponRewardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $coupon;
    public $totalSpent;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Coupon $coupon, float $totalSpent)
    {
        $this->user = $user;
        $this->coupon = $coupon;
        $this->totalSpent = $totalSpent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Chúc Mừng! Bạn Nhận Được Mã Giảm Giá Đặc Biệt',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.coupon-reward',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
