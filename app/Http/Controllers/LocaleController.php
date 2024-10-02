<?php

namespace App\Http\Controllers;

use App\Models\Locale;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class LocaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $locales = Locale::orderBy('created_at', 'desc')->get();
        $localeCount = DB::table('locales')->count();
        return Inertia::render('Locales/Locales', ['locales' => $locales, 'localeCount'=> $localeCount]);

       
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
        Locale::create($request->validate([
            'locale' => ['required'],
            'type' => ['required'],
            'capacity' => [''],
            ]));  
    }

    /**
     * Display the specified resource.
     */
    public function show(Locale $locale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locale $locale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Locale $locale)
    {
        $locale->locale = $request->locale;
        $locale->type = $request->type;
        $locale->capacity = $request->capacity;
        $locale->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Locale $locale)
    {
        $locale->delete();
    }
}
