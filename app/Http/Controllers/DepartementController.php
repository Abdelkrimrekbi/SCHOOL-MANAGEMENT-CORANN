<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $departements = Departement::orderBy('created_at', 'desc')->get();
        return Inertia::render('Departements/Departements', ['departements' => $departements]);

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
        Departement::create($request->validate([
            'departement' => ['required'],
            //'date'=>['required'],
            'description' => ['required'],
            ])); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement)
    {
        $departementToShow = Departement::where('id', $departement->id )->first();
        return Inertia::render('Departements/Show',['departement'=>$departementToShow]);
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departement $departement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departement $departement)
    {
        $departement->departement = $request->departement;
       // $departement->date = $request->date;
        $departement->description = $request->description;
        $departement->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departement $departement)
    {
        $departement->delete();
    }
}
