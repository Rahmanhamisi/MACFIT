<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GymController as ControllersGymController;
use App\Http\Controllers\GymController as HttpControllersGymController;
use App\Http\Controllers\GymController as AppHttpControllersGymController;
use App\Http\Controllers\GymController as GymControllerAlias;
use App\Http\Controllers\RoleController;
use App\Models\Bundle;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    //Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Protected Routes
Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
        
    });






Route::post('/saveRole', [RoleController::class, 'createRole']);
Route::get('/getRoles', [RoleController::class, 'readAllRoles']);
Route::get('/getRole/{id}', [RoleController::class,'readRole']);
Route::post('/updateRole/{id}', [RoleController::class,'updateRole']);
Route::delete('/updateRole/{id}', [RoleController::class,'deleteRole']);

Route::post('/saveCategory', [CategoryController::class, 'createCategory']);
Route::get('/getCategories', [CategoryController::class, 'readAllCategories']);
Route::get('/getCategory/{id}', [CategoryController::class,'readCategory']);
Route::post('/updateCategory/{id}', [CategoryController::class,'updateCategory']);
Route::delete('/updateCategory/{id}', [CategoryController::class,'deleteCategory']);

Route::post('/saveGym', [GymController::class, 'createGym']);
Route::get('/getGyms', [GymController::class, 'readAllGyms']);
Route::get('/getGym/{id}', [GymController::class,'readGyms']);
Route::post('/updateGym/{id}', [GymController::class,'updateGym']);
Route::delete('/deleteGym/{id}', [GymController::class,'deleteGyms']);

Route::post('/saveBundle', [BundleController::class, 'createBundle']);
Route::get('/getBundles', [BundleController::class, 'readAllBundles']);
Route::get('/getBundle/{id}', [BundleController::class,'readBundle']);
Route::post('/updateBundle/{id}', [BundleController::class,'updateBundle']);
Route::delete('/deleteBundle/{id}', [BundleController::class,'deleteBundle']);




