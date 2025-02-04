<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppVerificationCode;
use Carbon\Carbon;
use Twilio\Rest\Client;

class WhatsAppService
{
    protected $twilioClient;
    protected $fromNumber;

    public function __construct()
    {
        $this->twilioClient = new Client(
            config('services.twilio.account_sid'),
            config('services.twilio.auth_token')
        );
        $this->fromNumber = config('services.twilio.whatsapp_from');
    }

    /**
     * Generate and send verification code via WhatsApp
     */
    public function sendVerificationCode(string $whatsapp): WhatsAppVerificationCode
    {
        // Generate a random 6-digit code
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Save the code
        $verificationCode = WhatsAppVerificationCode::create([
            'whatsapp' => $whatsapp,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(15), // Code expires in 15 minutes
        ]);

        // Format message
        $message = "Seu código de acesso ao UdiFidelidade é: *{$code}*\n\n" .
                  "Este código expira em 15 minutos.\n" .
                  "Não compartilhe este código com ninguém.";

        // Send via Twilio
        $this->twilioClient->messages->create(
            "whatsapp:+{$whatsapp}", // To
            [
                'from' => "whatsapp:{$this->fromNumber}",
                'body' => $message
            ]
        );

        return $verificationCode;
    }

    /**
     * Verify the code
     */
    public function verifyCode(string $whatsapp, string $code): bool
    {
        $verificationCode = WhatsAppVerificationCode::where('whatsapp', $whatsapp)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verificationCode) {
            return false;
        }

        $verificationCode->markAsUsed();
        return true;
    }
}
