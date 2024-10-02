<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $configuration = Configuration::first();
        return Inertia::render('Configurations/Configurations', ['configuration' => $configuration]);
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
        $validatedData = $request->validate([
            'id' => 'nullable|exists:configurations,id',
            'name' => 'required',
            'phone' => '',
            'email' => '',
            'local' => '',
            'site' => '',
            'RC' => '',
            'IF' => '',
            'CNSS' => '',
            'patente' => '',
            'ICE' => '',
            'RIB' => '',
            'promotion' => 'required',
        ]);
        $configData = $validatedData;
        if ($request->hasFile('logo')) {
            $imagePath = $request->file('logo')->store('logo', 'public');
            $configData['logo'] = $imagePath;
        }

        if (isset($validatedData['id'])) {
            $configuration = Configuration::findOrFail($validatedData['id']);
            $configuration->update($configData);
        } else {
            Configuration::create($configData);
        }
    }

    public function sendImage()
    {
        $configuration = Configuration::first();
        return response()->json(['configuration' => $configuration]);
    }

    /**
     * Display the specified resource.
     */
    public function show(configuration $configuration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(configuration $configuration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, configuration $configuration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(configuration $configuration)
    {
        //
    }
}
