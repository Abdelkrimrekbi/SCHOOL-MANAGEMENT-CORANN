<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\group;

use App\Models\Level;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $student = Student::where('isChecked', 1)->orderBy('created_at', 'desc')->with(['level', 'group'])->paginate(10);
        $levels = Level::orderBy('created_at', 'desc')->get();
        $classes = group::orderBy('created_at', 'desc')->get();
        return Inertia::render('Students/Students', ['student' => $student, 'levels' => $levels, 'classes' => $classes]);
    }

    public function student() 
    {
        $student = Student::orderBy('created_at', 'desc')->with(['level', 'group'])->paginate(10);
        return response()->json(['student' => $student]);
    }

    public function getClassByLevel($id){
        $groups = Group::where("level_id", $id)->get();
        return redirect()->back()->with('groups', $groups);
    }

    // public function checkIfStudentExists($cne){
    //     Log::debug($cne);
    //     $studentExists = Student::where('CNE', $cne)->exists();
    //     Log::debug($studentExists);
    //     return response()->json(['exists' => $studentExists]);
    // }

    public function filteredStudents($id){
        $students = Student::where('class_id', $id)
        ->where('isChecked', 1)
        ->orderBy('created_at', 'desc')
        ->with(['level', 'group'])
        ->paginate(10);
        return redirect()->back()->with(['students' => $students]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function incrementStudentLevel()
    {
        $students = Student::all();
        foreach ($students as $student) {
            $student->niveau_etude += 1;
            $student->save();
        }
    }
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
        $validatedData = $request->validate([
            'date_birth' => ['required'],
            'name' => ['required'],
            'profession' => ['nullable'],
            'la_situation' => ['nullable'],
            'lieu_birth' => ['required'],
            'level_id' => ['required'],
            'class_id' => ['nullable'],
            'address' => ['required'],
            'phone' => ['nullable'],
            'niveau_etude' => ['required'],
            'name_school' => ['nullable'],
            'service_mensuel' => ['nullable'],
            'droit_annuel' => ['nullable'],
            'isChecked' => ['required'],
          
        ]);
        $validatedData['promotion'] = $configuration->promotion;
       

        
     
        Student::create($validatedData);
    }

    /**
     * Display the specified resource.
     */

    public function show(Student $student)
    {
        $studentShow = Student::with(['level', 'group', 'payment'])->where('id', $student->id)->firstOrFail();
        $totalRest = Payment::where('student_id', $student->id)->sum('rest');
       // log::debug("value id :",[$studentShow]);
        return Inertia::render('Students/Show', ['studentShow' => $studentShow,'totalRest' => $totalRest
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date_birth' => ['required'],
            'name' => ['required'],
            'profession' => ['nullable'],
            'la_situation' => ['nullable'],
            'lieu_birth' => ['required'],
            'level_id' => ['required'],
            'class_id' => ['nullable'],
            'address' => ['required'],
            'phone' => ['nullable'],
            'niveau_etude' => ['required'],
            'name_school' => ['nullable'],
            'service_mensuel' => ['nullable'],
            'droit_annuel' => ['nullable'],
            'isChecked' => ['required'],
          
           
        ]);
        $student = Student::find($id);
        $request["isChecked"] =  ($request["isChecked"] == true) ? 1 : 0;
        // Check if the student exists
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    
        // Update the student record with the request data
        $student->update($request->all());
       
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
    }
}
