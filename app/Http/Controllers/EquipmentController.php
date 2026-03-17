<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    // Create
    public function createEquipment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $equipment = new Equipment();
            $equipment->name = $validated['name'];
            $equipment->description = $validated['description'] ?? null;
            $equipment->save();

            return response()->json($equipment, 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to save Equipment',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Read All
    public function readAllEquipments()
    {
        try {
            $equipments = Equipment::all();
            return response()->json($equipments);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch Equipments',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Read One
    public function readEquipment($id)
    {
        try {
            $equipment = Equipment::findOrFail($id);
            return response()->json($equipment);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch the equipment',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Update
    public function updateEquipment(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $equipment = Equipment::findOrFail($id);
            $equipment->name = $validated['name'];
            $equipment->description = $validated['description'] ?? null;
            $equipment->save();

            return response()->json($equipment);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to update the equipment',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Delete
    public function deleteEquipment($id)
    {
        try {
            $equipment = Equipment::findOrFail($id);
            $equipment->delete();

            return response()->json([
                'message' => 'Equipment deleted successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to delete the equipment',
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}