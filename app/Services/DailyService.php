<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DailyService
{
    public function createRoom($name = null, $isPermanent = false)
    {
        $url = 'https://api.daily.co/v1/rooms';
        $headers = [
            'Authorization' => 'Bearer ' . env('DAILY_API_KEY'),
            'Content-Type' => 'application/json',
        ];

        $data = [
            'properties' => [
                'enable_chat' => false,
                'enable_screenshare' => true,
                'exp' => $isPermanent ? null : now()->addHours(1)->timestamp,
            ],
        ];

        if ($name) {
            $data['name'] = $name;
        }

        $response = Http::withHeaders($headers)->post($url, $data);

        return $response->json();
    }
}
