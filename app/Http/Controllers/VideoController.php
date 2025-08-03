<?php

namespace App\Http\Controllers;

use App\Services\DailyService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function start(Request $request, DailyService $daily)
    {
        $room = $daily->createRoom(); // kreira sobu

        return response()->json([
            'room_url' => $room['url'] ?? null
        ]);
    }
}
