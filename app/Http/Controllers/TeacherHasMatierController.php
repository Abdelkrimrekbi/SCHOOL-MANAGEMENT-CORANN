<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\TeacherHasMatier;
use App\Models\group;
use App\Models\Matiere;
use App\Models\Teachers;
use App\Models\Configuration;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TeacherHasMatierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {    
        $configuration = Configuration::orderBy('created_at', 'desc')->first();
        if (is_null($configuration) || is_null($configuration->promotion)) {
            return redirect()->back()->with(['error' => 'Veuillez remplir la promotion dans la configuration.'], 422);
        }
        $teacherHasMatier = TeacherHasMatier::orderBy('created_at', 'desc')->where('promotion', $configuration->promotion)->get();
       // $teacherHasMatier = TeacherHasMatier::orderBy('created_at', 'desc')->get();
        $teachers = Teachers::orderBy('created_at', 'desc')->get();
        $levels = Level::orderBy('created_at', 'desc')->get();
        return Inertia::render('TeacherHasMatier/TeacherHasMatier', ['teacherHasMatier' => $teacherHasMatier, 'teachers' => $teachers, 'levels' => $levels, 'configuration' => $configuration]);
    }

    public function getClassesByLevel($id)
    {
        $configuration = Configuration::orderBy('created_at', 'desc')->first();
        $classes = group::where('level_id', $id)->orderBy('created_at', 'desc')->where('promotion', $configuration->promotion)->get();
        $matiers = Matiere::where('level_id', $id)->orderBy('created_at', 'desc')->get();
        return response()->json(['classes' => $classes, 'matiers' => $matiers]);
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
        foreach ($request->input() as $matierTeacher) {
            $matier_id = $matierTeacher['matier_id'];
            $teacher_id = $matierTeacher['teacher_id'];
            $group_id = $matierTeacher['group_id'];
            $promotion = $configuration->promotion;

            if ($teacher_id !== null) {
                $teacherHasMatier = TeacherHasMatier::where(['group_id' => $group_id, 'matier_id' => $matier_id])->first();
                if ($teacherHasMatier) {
                    $teacherHasMatier->update([
                        'teacher_id' => $teacher_id
                    ]);
                } else {
                    TeacherHasMatier::create([
                        'matier_id' => $matier_id,
                        'teacher_id' => $teacher_id,
                        'group_id' => $group_id,
                        'promotion' => $promotion,
                    ]);
                }
            }
        }
    }

    public function getMatierTeacherByLevel($id)
    {
        $matierTeacher = TeacherHasMatier::where('group_id', $id)->orderBy('created_at', 'desc')->get();
        return response()->json(['matierTeacher' => $matierTeacher]);
    }

    public function findClasse($id)
    {
        $groupExists = TeacherHasMatier::where('group_id', $id)->exists();
        return response()->json(['groupExists' => $groupExists]);
    }

    public function findMatier($id)
    {
        $matierExists = TeacherHasMatier::where('matier_id', $id)->exists();
        return response()->json(['matierExists' => $matierExists]);
    }

    /**
     * Display the specified resource.
     */
    public function show(TeacherHasMatier $teacherHasMatier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeacherHasMatier $teacherHasMatier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        dd($request->all(), 'Update');
        foreach ($request->all() as $item) {
            $teacher = TeacherHasMatier::where('group_id', $id)
                ->where('matier_id', $item['matier_id'])
                ->first();

            if ($teacher != null) {
               // Log::info('Avant mise à jour:', $teacher->toArray());

                $teacher->update([
                    "teacher_id" => $item['teacher_id']
                ]);
            } else {
              //  Log::info('Non mise à jour:');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeacherHasMatier $teacherHasMatier)
    {
        //
    }
}
