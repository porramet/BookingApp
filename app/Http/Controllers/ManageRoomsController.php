<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Building;
use App\Models\Status;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ManageRoomsController extends Controller
{
    public function index()
    {
        // Fetch all buildings with pagination and search
        $buildings = Building::when(request('search'), function($query) {
                        $query->where('building_name', 'like', '%'.request('search').'%');
                    })
                    ->paginate(12);
                    
        // Get room statistics
        $rooms = Room::all();
        $status = Status::all();
        
        // Get total counts
        $totalBuildings = Building::count();
        $totalRooms = Room::count();
        
        return view('dashboard.manage_rooms', compact(
            'buildings', 
            'rooms', 
            'status',
            'totalBuildings',
            'totalRooms'
        ));
    }

    public function showRooms($buildingId)
    {
        // Ensure building exists and get its ID
        $building = Building::findOrFail($buildingId);
        
        // Get rooms for this building with search and pagination
        // Get total counts for available and unavailable rooms
        $totalCount = Room::where('building_id', $building->id)->count();
        
        $availableCount = Room::where('building_id', $building->id)
                            ->where('status_id', 2)
                            ->count();
                            
        $unavailableCount = Room::where('building_id', $building->id)
                              ->where('status_id', 1)
                              ->count();

        // Get paginated rooms
        $rooms = Room::with(['building', 'status'])
                    ->where('building_id', $building->id)
                    ->when(request('search'), function($query) {
                        $query->where('room_name', 'like', '%'.request('search').'%');
                    })
                    ->paginate(12);
                    
        $buildings = Building::all();
        $status = Status::all();
        
        return view('dashboard.rooms', compact(
            'rooms', 
            'building', 
            'buildings', 
            'status',
            'totalCount',
            'availableCount',
            'unavailableCount'
        ));
    }

    public function fetchRooms()
    {
        return Room::all();
    }

    private function generateRoomId($buildingId)
    {
        // Get the maximum room_id for this building
        $maxRoomId = Room::where('building_id', $buildingId)
                        ->max('room_id');
        
        // Return next available room_id
        return $maxRoomId ? $maxRoomId + 1 : 1;
    }

    public function store(Request $request)
    {
        try {
            // Log incoming request data
            Log::info('Room creation request data:', $request->all());

            // Validate the request
            $validated = $request->validate([
                'building_id' => 'required|exists:buildings,id',
                'room_name' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'class' => 'required|string|max:255',
                'room_details' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'service_rates' => 'required|numeric|min:0',
            ]);

            // Log successful validation
            Log::info('Room data validated successfully:', $validated);

            // Create new room
            $room = new Room();
            $room->building_id = $validated['building_id'];
            $room->room_id = $this->generateRoomId($validated['building_id']);
            $room->room_name = $validated['room_name'];
            $room->capacity = $validated['capacity'];
            $room->class = $validated['class'];
            $room->room_details = $validated['room_details'];
            $room->service_rates = $validated['service_rates'];
            $room->status_id = 2; // Set status to empty room

                // Handle image upload with enhanced validation and debugging
                if ($request->hasFile('image')) {
                    try {
                        $image = $request->file('image');
                        
                        // Validate file type and size
                        if (!$image->isValid()) {
                            Log::error('Invalid file upload attempt', [
                                'name' => $image->getClientOriginalName(),
                                'error' => $image->getErrorMessage()
                            ]);
                            throw new \Exception('Invalid file upload: ' . $image->getErrorMessage());
                        }
                        
                        // Verify file extension
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                        $extension = strtolower($image->getClientOriginalExtension());
                        if (!in_array($extension, $allowedExtensions)) {
                            throw new \Exception('Invalid file type. Allowed types: ' . implode(', ', $allowedExtensions));
                        }
                        
                        // Generate unique filename with original extension
                        $imageName = time() . '_' . uniqid() . '.' . $extension;
                        
                        // Verify storage directory exists
                        $storagePath = storage_path('app/public/room_images');
                        if (!is_dir($storagePath)) {
                            if (!mkdir($storagePath, 0755, true)) {
                                throw new \Exception('Failed to create storage directory');
                            }
                        }
                        
                        // Store image and handle errors
                        $imagePath = $image->storeAs('public/room_images', $imageName);
                        if (!$imagePath) {
                            throw new \Exception('Failed to store image');
                        }
                        
                        $room->image = 'room_images/' . $imageName;
                        Log::info('Room image uploaded successfully:', [
                            'path' => $imagePath,
                            'size' => $image->getSize(),
                            'mime' => $image->getMimeType(),
                            'storage' => $storagePath
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Image upload failed: ' . $e->getMessage(), [
                            'file' => $image->getClientOriginalName(),
                            'error' => $e->getTraceAsString()
                        ]);
                        return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
                    }
                }


            // Save the room
            $room->save();
            Log::info('Room created successfully:', $room->toArray());

            return redirect()->route('manage_rooms.show', $room->building_id)->with('success', 'Room created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating room: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Failed to create room. Please try again.');
        }
    }

    public function edit($room)
    {
        try {
            $room = Room::findOrFail($room);
            $status = Status::all();
            return view('dashboard.edit_room', compact('room', 'status'));
        } catch (\Exception $e) {
            Log::error('Error fetching room for edit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to fetch room details');
        }
    }

    public function update(Request $request, $room)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'building_id' => 'required|exists:buildings,id',
                'room_name' => 'required|string|max:255',
                'capacity' => 'required|integer|min:1',
                'status_id' => 'required|exists:status,status_id',
                'class' => 'required|string|max:255',
                'room_details' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'service_rates' => 'required|numeric|min:0',
            ]);

            // Find the room
            $room = Room::findOrFail($room);

                // Handle image deletion and upload with enhanced validation
                if ($request->hasFile('image')) {
                    try {
                        $image = $request->file('image');
                        
                        // Validate file type and size
                        if (!$image->isValid()) {
                            Log::error('Invalid file upload attempt', [
                                'name' => $image->getClientOriginalName(),
                                'error' => $image->getErrorMessage()
                            ]);
                            throw new \Exception('Invalid file upload: ' . $image->getErrorMessage());
                        }
                        
                        // Verify file extension
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                        $extension = strtolower($image->getClientOriginalExtension());
                        if (!in_array($extension, $allowedExtensions)) {
                            throw new \Exception('Invalid file type. Allowed types: ' . implode(', ', $allowedExtensions));
                        }
                        
                        // Delete old image if exists
                        if ($room->image && Storage::exists('public/' . $room->image)) {
                            if (!Storage::delete('public/' . $room->image)) {
                                Log::warning('Failed to delete old image', ['path' => $room->image]);
                            }
                        }
                        
                        // Generate unique filename with original extension
                        $imageName = time() . '_' . uniqid() . '.' . $extension;
                        
                        // Verify storage directory exists
                        $storagePath = storage_path('app/public/room_images');
                        if (!is_dir($storagePath)) {
                            if (!mkdir($storagePath, 0755, true)) {
                                throw new \Exception('Failed to create storage directory');
                            }
                        }
                        
                        // Store new image
                        $imagePath = $image->storeAs('public/room_images', $imageName);
                        if (!$imagePath) {
                            throw new \Exception('Failed to store image');
                        }
                        
                        $room->image = 'room_images/' . $imageName;
                        Log::info('Room image updated successfully:', [
                            'path' => $imagePath,
                            'size' => $image->getSize(),
                            'mime' => $image->getMimeType(),
                            'storage' => $storagePath
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Image update failed: ' . $e->getMessage(), [
                            'file' => $image->getClientOriginalName(),
                            'error' => $e->getTraceAsString()
                        ]);
                        return redirect()->back()->with('error', 'Failed to update image: ' . $e->getMessage());
                    }
                }


            // Update room details
            $room->update($validated);

            Log::info('Room updated successfully:', $room->toArray());
            return redirect()->route('manage_rooms.show', $room->building_id)->with('success', 'Room updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating room: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Failed to update room. Please try again.');
        }
    }

    public function destroy($room_id)
{
    // ค้นหาห้องที่ต้องการลบ
    $room = Room::findOrFail($room_id);

    // ลบรูปภาพ (ถ้ามี)
    if ($room->image) {
        Storage::delete('public/' . $room->image);
    }

    // ลบห้อง
    $room->delete();

    // ส่งกลับไปยังหน้าก่อนหน้าพร้อมข้อความสำเร็จ
    return redirect()->back()->with('success', 'ลบห้องเรียบร้อยแล้ว');
}
}
