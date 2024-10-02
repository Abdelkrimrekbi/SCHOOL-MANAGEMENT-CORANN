<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $levels = Level::orderBy('created_at', 'desc')->get();
        return Inertia::render('Levels/Levels', ['levels' => $levels]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){   
        Level::create($request->validate([
            'level' => ['required'],
            'price' => ['required'],
            'description' => [''],
            ]));  
    }

    /**
     * Display the specified resource.
     */
    public function show(Level $level)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        $level->level = $request->level;
        $level->price = $request->price;
        $level->description = $request->description;
        $level->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        $level->delete();
    }
}
