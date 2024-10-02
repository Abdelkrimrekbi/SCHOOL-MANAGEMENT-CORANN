<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
use App\Models\Level;
use Inertia\Inertia;
use App\Models\Configuration;

class MatiereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$teachers=Teachers::orderBy('created_at', 'desc')->get();
        $matieres= Matiere::orderBy('created_at', 'desc')->get();
        // $levels = Level::orderBy('created_at', 'desc')->withTrashed()->get();
        $levels = Level::whereNull('deleted_at')->orderBy('created_at', 'desc')->get();
        return Inertia::render('Matieres/Matieres', ['matieres' => $matieres, 'levels' => $levels,]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly creatcompoed resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required'],
            'level_id' => ['required']
        ]);
        matiere::create($validateData);
    }

    /**
     * Display the specified resource.
     */
    public function show(Matiere $matiere)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Matiere $matiere)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $matiere = Matiere::find($id);
    
        $validateData = $request->validate([
            'id' => ['required'],
            'name' => ['required'],
            'level_id' => ['required'],
        ]);
    
        // Check if 'promotion' is present in the request
    
        // Update the specified resource in storage
        $matiere->update($validateData);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $matiere = Matiere::findOrFail($id)->delete();
    }
}
