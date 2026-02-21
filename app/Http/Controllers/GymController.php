<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use Illuminate\Http\Request;

class GymController extends Controller
{

    public function createGym(Request $request){
        $validated= $request->validate([
            'name'=>'required|string',
            'longitude'=>'required|string',
            'latitude'=>'required|string',
            'description'=>'string|max:1000.',
        ]);

        $gym = new Gym();
        $gym->name = $validated['name'];
        $gym->name = $validated['longitude'];
        $gym->name = $validated['latitude'];
        $gym->description = $validated('description');

        try{
            $gym->save();
            return response()->json($gym);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to save Gym',
                'message'=>$exception->getMessage(), 500
            ]);
        }
    }
    public function readAllGyms(){
        try{
            $gyms = Gym::all();
            return response()->json($gyms);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to fetch Gyms',
                'message'=>$exception->getMessage()
            ]);
        }
    }
    public function readGym($id){
        try{
            $gym =Gym::findOrFail($id);
            return response()->json($gym);
        }
         catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to fetch the gym',
                'message'=>$exception->getMessage()
               ]);
    }
}
    public function updateGym(Request $request, $id){
    $validated= $request->validate([
            'name'=>'required|string',
            'description'=>'nullable|string|max:1000',
        ]);

        try{
            $existingGym = Gym::findOrFail($id);
            $existingGym->name = $validated['name'];
            $existingGym = $validated['longitude'];
            $existingGym = $validated['latitude'];
            $existingGym->description = $validated['description'];
            $existingGym->save();
            return response()->json($existingGym);   
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to fetch Gyms',
                'message'=>$exception->getMessage()
            ]);
    }
}
public function deleteGym($id){
    try{
        $gym=Gym::findOrFail($id);
        $gym->delete();
        return response("Gym deleted successfully");

    }
    catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to delete Gyms',
                'message'=>$exception->getMessage()
            ]);
}
}
}