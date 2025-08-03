<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Http;
use Exception;

class DailyWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Alternativni način logiranja ako je laravel.log prespor
        $debugMessage = "Daily Webhook: Request received at " . now() . "\n";
        $debugMessage .= "Payload: " . json_encode($request->all(), JSON_PRETTY_PRINT) . "\n";
        file_put_contents(storage_path('webhook-debug.log'), $debugMessage, FILE_APPEND);

        try {
            $eventType = $request->input('type');
            $roomName = $request->input('payload.room');

            if ($eventType === 'participant.left') {
                if (!$roomName) {
                    file_put_contents(storage_path('webhook-debug.log'), "Error: Room name not found in payload.\n", FILE_APPEND);
                    return response()->json(['message' => 'Room name not found'], 400);
                }

                // Uvedite kratku pauzu da Daily.co API ažurira stanje sobe
                sleep(2); // Pauza od 2 sekunde

                file_put_contents(storage_path('webhook-debug.log'), "Trying to find room '{$roomName}' in DB...\n", FILE_APPEND);
                $room = Room::where('daily_room_url', 'like', "%{$roomName}")->first();

                if ($room) {
                    file_put_contents(storage_path('webhook-debug.log'), "Found room ID {$room->id}\n", FILE_APPEND);

                    $dailyApiKey = env('DAILY_API_KEY');
                    $response = Http::withHeaders(['Authorization' => 'Bearer ' . $dailyApiKey])
                        ->get("https://api.daily.co/v1/rooms/{$roomName}/sessions");

                    if ($response->successful()) {
                        $sessions = $response->json();
                        $activeSessionsCount = isset($sessions['data']) ? count($sessions['data']) : 0;

                        file_put_contents(storage_path('webhook-debug.log'), "API call successful. Active sessions: {$activeSessionsCount}\n", FILE_APPEND);

                        if ($activeSessionsCount === 0) {
                            file_put_contents(storage_path('webhook-debug.log'), "Room is empty! Resetting URL...\n", FILE_APPEND);
                            $room->daily_room_url = null;
                            $room->save();
                        }
                    } else {
                        file_put_contents(storage_path('webhook-debug.log'), "API call failed. Status: {$response->status()}\n", FILE_APPEND);
                    }
                } else {
                    file_put_contents(storage_path('webhook-debug.log'), "Room not found in DB.\n", FILE_APPEND);
                }
            }
        } catch (Exception $e) {
            file_put_contents(storage_path('webhook-debug.log'), "ERROR: {$e->getMessage()}\n", FILE_APPEND);
            return response()->json(['error' => 'Server error'], 500);
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }
}
