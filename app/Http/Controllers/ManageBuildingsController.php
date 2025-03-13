<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;


class ManageBuildingsController extends Controller
{
    // แสดงรายการอาคาร
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $buildings = Building::when($search, function($query) use ($search) {
            return $query->where('building_name', 'like', '%'.$search.'%')
                       ->orWhere('citizen_save', 'like', '%'.$search.'%');
        })->paginate(15);

        $totalBuildings = Building::count();
        
        return view('dashboard.manage_buildings', compact('buildings', 'totalBuildings'));

    }

    // เพิ่มอาคาร
    public function store(Request $request)
    {
        // เพิ่ม validation สำหรับ `building_name` และ `citizen_save`
        $request->validate([
            'building_name' => 'required|string|max:255',
            'citizen_save' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        // Log the request data for debugging
        \Log::info('Building Store Request Data:', $request->all());

        // ส่วนของการเพิ่มข้อมูล
        $building = new Building();
        $building->building_name = $request->building_name;
        $building->citizen_save = $request->citizen_save;
        $building->date_save = now();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('buildings', 'public');
            $building->image = $imagePath;
        }

        $building->save();


        return redirect()->route('manage_rooms.index')->with('success', 'Building added successfully.');

    }

    public function update(Request $request, $id)
    {
        $building = Building::find($id);

        if (!$building) {
            return response()->json(['message' => 'ไม่พบอาคาร'], 404);
        }

        $building->building_name = $request->building_name;
        $building->citizen_save = $request->citizen_save;
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($building->image && Storage::disk('public')->exists($building->image)) {
                Storage::disk('public')->delete($building->image);
            }
            
            $imagePath = $request->file('image')->store('buildings', 'public');
            $building->image = $imagePath;
        }

        $building->save();


        return redirect()->route('manage_rooms.index')->with('success', 'Building updated successfully.');
    }

    public function destroy($id)
    {
        // 1. หาอาคารที่ต้องการลบ
        $building = Building::findOrFail($id);

        // 2. ลบข้อมูล
        $building->delete();

        // 3. Redirect พร้อมข้อความแจ้งเตือน
        return redirect()->back()
            ->with('success', 'ลบอาคารเรียบร้อยแล้ว');
    }
}
