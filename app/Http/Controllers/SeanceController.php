<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Seance;
use App\Models\Student;
use App\Models\Locale;
use App\Models\TeacherHasMatier;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class SeanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $seance = Seance::orderBy('created', 'desc')->get();
        // return Inertia::render('Absence/Absence', ['seance' => $seance]);
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
        log::debug("value :",$request->all());
        $seance = Seance::create($request->validate([
            // 'startSeance' => ['required'],
            // 'finSeance' => ['required'],
            // 'type' => ['required'],
            // 'local_id' => ['required'],
            'teacherHasMatier_id' => ['required']
        ]));

        return redirect()->back()->with('flash', ['seance' => $seance]);
    }

    public function getStudentsByGroup($id) {
      //  log::debug("value id :",[$id]);
        $student = Student::where('class_id', $id)->get();
    //    log::debug("value student :",[$student]);
        return response()->json(['student' => $student]);
       // return redirect()->back()->with(["message" => $student]);
    }

    public function getLocal($id) {
        $local = Locale::where('id',$id)->first();
        return response()->json(['local' => $local]);
    }
    public function getAbsencesBySeance($id) {
        $absences = Absence::where('seance_id', $id)->with('seance', 'student')->get();
        // log::debug("value student :",[$absences]);
        return response()->json(['absence' => $absences]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Seance $seance)
    {  
       // log::debug("seanceseance :",[$seance]);
        $seanceId = $seance->id;
        $seanceTeaMa = $seance->teacherHasMatier_id;
        $groupMatiereTeacher =TeacherHasMatier::where('id',$seanceTeaMa)->with('group', 'matiere')->get();
       // log::debug("value selectedGroupId :",[$selectedGroupId]);
       //  $absences = Absence::where('seance_id',$seanceId)->with('seance', 'student')->get();
        $seances = Seance::with('teacherSubjectGroups.group.student', 'absence')->where('id',$seanceId)->get();
       log::debug("value selectedGroupId :",[$seances]);
        $absences = Absence::where('seance_id',$seanceId)->get();
        
       return Inertia::render('Absence/Show', ['absences' => $absences , 'groupMatiereTeacher' => $groupMatiereTeacher,'seances' =>$seances, 'seanceId' => $seanceId]);

       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seance $seance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seance $seance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seance $seance)
    {
        $seance->delete();
    }
}
