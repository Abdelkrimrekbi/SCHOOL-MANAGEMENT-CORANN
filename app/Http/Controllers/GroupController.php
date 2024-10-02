<?php

namespace App\Http\Controllers;

use App\Models\group;
use App\Models\Level;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Configuration;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $configuration = Configuration::orderBy('created_at', 'desc')->first();
        //  if (is_null($configuration)) {
        //     return Inertia::render('Students/Students', [
        //         'groups' => fn () => collect([]),
        //         'levels' => collect([]),
        //         'error' => 'No configuration found',
        //     ]);
        // }
        $groups = Group::orderBy('created_at', 'desc')->where('promotion', $configuration->promotion)->get();
        $levels = Level::orderBy('created_at', 'desc')->withTrashed()->get();
        return Inertia::render('Classes/Classes', ['groups' => $groups, 'levels' => $levels]);
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
        $configuration = Configuration::orderBy('created_at', 'desc')->first();
        if (is_null($configuration) || is_null($configuration->promotion)) {
            return redirect()->back()->with(['error' => 'Veuillez remplir la promotion dans la configuration.'], 422);
        }

        $configuration = Configuration::orderBy('created_at', 'desc')->first();
        $promotion = $configuration->promotion;
        $validateData = $request->validate([
            'group' => ['required'],
            'nbr_students' => [''],
            'level_id' => ['required']
        ]);
        $validateData['promotion'] = $promotion;
        group::create($validateData);
    }

    /**
     * Display the specified resource.
     */
    public function show(group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $group = group::find($id);
        $group->update([
            "group" => $request->group,
            "nbr_students" => $request->nbr_students,
            "level_id" => $request->level_id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $groupe = Group::findOrFail($id)->delete();
    }
}
