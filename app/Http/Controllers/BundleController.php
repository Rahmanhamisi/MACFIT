<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;

class BundleController extends Controller
{

    public function createBundle(Request $request){
        $validated= $request->validate([
            'name'=>'required|string|unique:bundles,name',
            'start_time'=>'required|string',
            'duration'=>'required|string',
            'description'=>'required|string|max:1000',
            'category_id'=>'required|exists:categories,id',
            
        ]);

        $bundle = new Bundle();
        $bundle->name = $validated['name'];
        $bundle->start_time = $validated['start_time'];
        $bundle->duration = $validated['duration'];
        $bundle->value = $validated['value'];
        $bundle->description = $validated['description'];
        $bundle->category_id = $validated['category_id'];

        try{
            $bundle->save();
            return response()->json($bundle);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to save Bundle',
                'message'=>$exception->getMessage(), 500
            ]);
        }
    }
    public function readAllBundles(){
        try{
            $bundles = Bundle::join('categories','bundles.category_id', '=', 'categories.id')
                              ->select('bundles.*','categories.name as category_name')
                              ->get();
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to fetch Bundles',
                'message'=>$exception->getMessage()
            ]);
        }
    }
    public function readBundle($id){
        try{
            $bundle =Bundle::join('categories','bundles.category_id', '=', 'categories.id')
                              ->select('bundles.*','categories.name as category_name')
                              ->where('bundles.id', $id)
                              ->first();
            return response()->json($bundle);
        }
         catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to fetch the bundle',
                'message'=>$exception->getMessage()
               ]);
    }
}
    public function updateBundle(Request $request, $id){
    $validated= $request->validate([
            'name'=>'required|string',
            'description'=>'nullable|string|max:1000',
        ]);

        try{
            $existingBundle = Bundle::findOrFail($id);
            $existingBundle->name = $validated['name'];
            $existingBundle = $validated['longitude'];
            $existingBundle = $validated['latitude'];
            $existingBundle->description = $validated['description'];
            $existingBundle->longitude = $validated['longitude'];
            $existingBundle->latitude = $validated['latitude'];
            $existingBundle->save();
            return response()->json($existingBundle);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to fetch Bundles',
                'message'=>$exception->getMessage()
            ]);
    }
}
public function deleteBundle($id){
    try{
        $bundle=Bundle::findOrFail($id);
        $bundle->delete();
        return response("Bundle deleted successfully");

    }
    catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to delete Bundles',
                'message'=>$exception->getMessage()
            ]);
    }
}
}