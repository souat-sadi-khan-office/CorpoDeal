<?php

namespace App\CPU;
use GuzzleHttp\Client;

class SmsHelper {
    private $apiUrl = 'https://sysadmin.muthobarta.com/api/v1/send-sms';
    private static $authToken = 'Token 753b5503a9974e3d35e45acc4a12a2d18e4e72b6';
    private $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function sendSms($receiver, $msg) {
        $data = [
            'receiver' => is_array($receiver) ? implode(',', $receiver) : $receiver,
            'message' => $msg,
            'remove_duplicate' => true
        ];

        try {
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => self::$authToken
                ],
                'json' => $data
            ]);

            return [
                'status_code' => $response->getStatusCode(),
                'response' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (\Exception $e) {
            return [
                'status_code' => 500,
                'error' => $e->getMessage()
            ];
        }
    }
}