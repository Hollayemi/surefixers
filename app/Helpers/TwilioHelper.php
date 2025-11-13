<?php

namespace App\Helpers;

use Twilio\Rest\Client;
use Exception;

class TwilioHelper
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $this->from = env('TWILIO_PHONE_NUMBER');
        
        if ($sid && $token) {
            $this->client = new Client($sid, $token);
        }
    }

    public function sendMessage($to, $message)
    {
        try {
            if (!$this->client) {
                throw new Exception('Twilio credentials not configured');
            }

            $response = $this->client->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );

            return [
                'success' => true,
                'sid' => $response->sid,
                'message' => 'Message sent successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function sendBulkMessages($recipients, $message)
    {
        $results = [];
        foreach ($recipients as $recipient) {
            $results[] = $this->sendMessage($recipient, $message);
        }
        return $results;
    }
}