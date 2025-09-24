<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $email;
    public $messageContent;
    /**
     * Create a new message instance.
     */
        public function __construct(string $name, string $email, string $messageContent)
    {
        $this->name = $name;
        $this->email = $email;
        $this->messageContent = $messageContent;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Địa chỉ email của người gửi câu hỏi sẽ được đặt làm địa chỉ "Reply-To"
            // Giúp Admin có thể bấm "Trả lời" trực tiếp trong hòm thư của họ
            replyTo: [
                new Address($this->email, $this->name),
            ],
            subject: '[BookHaven Contact] - Tin nhắn mới từ ' . $this->name,
        );
    }

    /**
     * Get the message content definition.
     */
     public function content(): Content
    {
        return new Content(
            // Sử dụng view 'emails.contact-form'
            view: 'emails.contact-form',
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
