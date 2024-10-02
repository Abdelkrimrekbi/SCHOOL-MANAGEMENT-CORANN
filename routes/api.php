<?php

use App\Http\Controllers\ConfigurationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\TeacherHasMatierController;
use App\Http\Resources\UserResource;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return UserResource::make($request->user());
});*/

Route::middleware([
    'auth:sanctum'
])->group(function () {

    Route::get('/user', function (Request $request) {
        return UserResource::make($request->user());
    });
});

Route::post('locale', function () {
    // Validate
    $validated = request()->validate([
        'language' => ['required'],
    ]);
    // Set locale
    App::setLocale($validated['language']);
    // Session
    session()->put('locale', $validated['language']);
    // Response
    return redirect()->back();
});
Route::get('/get-classes-by-level/{id}', [TeacherHasMatierController::class, 'getClassesByLevel']);
Route::get('/get-matierTeacher-by-class/{id}', [TeacherHasMatierController::class, 'getMatierTeacherByLevel']);
Route::get('/get-findmatierTeacher-by-class/{id}', [TeacherHasMatierController::class, 'findClasse']);
Route::get('/config', [ConfigurationController::class, 'sendimage']);
// Route::get('/logo-school', [\App\Http\Controllers\ConfigurationController::class, 'sendImage']);
