<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    //

    public function index(Request $request)
    {

        $query=Room::query();
        if ($request->has('kategorije') && !empty($request->kategorije)) {
            $query->whereIn('kategorija', $request->kategorije);
        }

        if ($request->has('naziv') && $request->naziv != '') {
            $query->where('naziv', 'like', '%' . $request->naziv . '%');
        }

        $sobe = $query->latest()->simplePaginate(6)->appends($request->all());

        return view('sobe.index', compact('sobe'));
    }
    public function show(Room $room){
        return view('sobe.show', compact('room'));
    }
}
