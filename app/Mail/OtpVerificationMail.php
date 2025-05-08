<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The OTP code for verification
     * 
     * @var string
     */
    public $otpCode;

    /**
     * The user's name
     * 
     * @var string
     */
    public $userName;

    /**
     * Expiration time in minutes
     * 
     * @var int
     */
    public $expirationTime;

    /**
     * Create a new message instance.
     *
     * @param string $otpCode
     * @param string $userName
     * @param int $expirationTime
     */
    public function __construct(
        string $otpCode,
        string $userName,
        int $expirationTime = 15
    ) {
        $this->otpCode = $otpCode;
        $this->userName = $userName;
        $this->expirationTime = $expirationTime;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: config('app.name') . ' - OTP Verification Code',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.otp-verification',
            with: [
                'otpCode' => $this->otpCode,
                'userName' => $this->userName,
                'expirationTime' => $this->expirationTime,
                'appName' => config('app.name'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}