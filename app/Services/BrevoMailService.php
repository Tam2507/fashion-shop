<?php

namespace App\Services;

use GuzzleHttp\Client;

class BrevoMailService
{
    private string $apiKey;
    private string $fromEmail;
    private string $fromName;

    public function __construct()
    {
        $this->apiKey    = env('BREVO_API_KEY', '');
        $this->fromEmail = env('MAIL_FROM_ADDRESS', 'noreply@example.com');
        $this->fromName  = env('MAIL_FROM_NAME', 'Fashion Shop');
    }

    public function send(string $toEmail, string $toName, string $subject, string $htmlContent): bool
    {
        if (empty($this->apiKey)) {
            \Log::error('Brevo API key not configured');
            return false;
        }

        try {
            $client = new Client(['timeout' => 10]);
            $response = $client->post('https://api.brevo.com/v3/smtp/email', [
                'headers' => [
                    'api-key'      => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ],
                'json' => [
                    'sender'     => ['name' => $this->fromName, 'email' => $this->fromEmail],
                    'to'         => [['email' => $toEmail, 'name' => $toName]],
                    'subject'    => $subject,
                    'htmlContent' => $htmlContent,
                ],
            ]);

            return $response->getStatusCode() === 201;
        } catch (\Exception $e) {
            \Log::error('Brevo send failed: ' . $e->getMessage());
            return false;
        }
    }
}
