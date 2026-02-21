<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function createCategory(Request $request){
        $validated= $request->validate([
            'name'=>'required|string|unique;categories,name',
            'description'=>'nullable|string|max:1000',
        ]);

        $category = new Category();
        $category->name = $validated['name'];
        $category->description = $validated('description');

        try{
            $category->save();
            return response()->json($category);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to save Category',
                'message'=>$exception->getMessage(), 500
            ]);
        }
    }
    public function readAllCategories(){
        try{
            $categories = Category::all();
            return response()->json($categories);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to fetch Categories',
                'message'=>$exception->getMessage()
            ]);
        }
    }
    public function readCategory($id){
        try{
            $category = Category::findOrFail($id);
            return response()->json($category);
        }
         catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to fetch the category',
                'message'=>$exception->getMessage()
               ]); 
    }
}
    public function updateCategory(Request $request, $id){
    $validated= $request->validate([
            'name'=>'required|string',
            'description'=>'nullable|string|max:1000',
        ]);

        try{
            $existingCategory = Category::findOrFail($id);
            $existingCategory->name = $validated['name'];
            $existingCategory->description = $validated['description'];
            $existingCategory->save();
            return response()->json($existingCategory);   
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to fetch Category',
                'message'=>$exception->getMessage()
            ]);
    }
}
    public function deleteCategory($id){
    try{
        $category=Category::findOrFail($id);
        $category->delete();
        return response("Category deleted successfully");

    }
    catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to delete Categories',
                'message'=>$exception->getMessage()
            ]);
}
}
}