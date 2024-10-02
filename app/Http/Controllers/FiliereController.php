<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Level;
use App\Models\Departement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FiliereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $levels = Level::orderBy('created_at', 'desc')->get();
        $filieres = Filiere::orderBy('created_at', 'desc')->get();
        $departements = Departement::orderBy('created_at', 'desc')->get();
        return Inertia::render('Filieres/Filieres', ['levels' => $levels , 'filieres'=> $filieres , 'departements'=> $departements]);
        
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
    public function store(Request $request)
    {
        Filiere::create($request->validate([
            'filiere' => ['required'],
            'level_id' => ['required'],
         //   'departement_id' => ['required'],
            ]));  
    }

    /**
     * Display the specified resource.
     */
    public function show(Filiere $filiere)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filiere $filiere)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Filiere $filiere)
    {
        $filiere->filiere = $request->filiere;
        $filiere->level_id = $request->level_id;
       // $filiere->departement_id = $request->departement_id;
        $filiere->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filiere $filiere)
    {
        $filiere->delete();
    }
}
