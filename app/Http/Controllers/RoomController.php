<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Building;

class RoomController extends Controller
{
    /**
     * Display a listing of all rooms.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::with(['building', 'status'])->get();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Display rooms filtered by type (class).
     *
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function byType($type)
    {
        $rooms = Room::with(['building', 'status'])
            ->where('class', $type)
            ->get();
        $title = "ประเภทห้อง: $type";
        return view('rooms.filtered', compact('rooms', 'title'));
    }

    /**
     * Display rooms filtered by building.
     *
     * @param int $building_id
     * @return \Illuminate\Http\Response
     */
    public function byBuilding($building_id)
    {
        $building = Building::findOrFail($building_id);
        $rooms = Room::with(['building', 'status'])
            ->where('building_id', $building_id)
            ->get();
        $title = "ห้องในอาคาร: {$building->building_name}";
        return view('rooms.filtered', compact('rooms', 'title'));
    }

    /**
     * Display popular rooms.
     *
     * @return \Illuminate\Http\Response
     */
    public function popular()
    {
        // In a real application, you might sort by booking count or ratings
        // Here we're just showing all rooms as an example
        $rooms = Room::with(['building', 'status'])->get();
        $title = "ห้องยอดนิยม";
        return view('rooms.filtered', compact('rooms', 'title'));
    }
}