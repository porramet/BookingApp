<?php

namespace App\Http\Controllers;

use App\Models\Building; // Ensure the Building model is imported
use App\Models\Room; // Ensure the Room model is imported
use Illuminate\Http\Request; // Ensure the Request class is imported
use Illuminate\Support\Facades\Response; // Import Response facade for JSON responses
use Illuminate\Support\Facades\Auth;

class BuildingController extends Controller
{
    /**
     * Display a listing of all buildings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buildings = Building::with('rooms')->get();
        $rooms = Room::all(); // Retrieve all rooms
        return view('booking', compact('buildings', 'rooms')); // Pass rooms to the view
    }

    public function fetchRooms($id)
    {
        $rooms = Room::where('building_id', $id)->get(); // Assuming 'building_id' is the foreign key in the rooms table
        return response()->json($rooms);
    }

    public function show($id)
    {
        $building = Building::with('rooms')->findOrFail($id);
        return view('buildings.show', compact('building')); // This line remains unchanged
    }
}
