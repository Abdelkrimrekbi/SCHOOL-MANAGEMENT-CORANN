<?php

namespace App\Http\Controllers;

use App\Models\Exame;
use App\Models\group;
use App\Models\TeacherHasMatier;
use Illuminate\Http\Request;
use App\Models\Locale;
use App\Models\Teachers;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Student;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ExameController extends Controller
{
    
    public function index()
    {
        $userId = auth()->id();
        $teacher = Teachers::where("user_id", auth()->id())->first();

        if($teacher) {
            //  $userId = auth()->id();
            //  $userID = User::find($userId)->Teacher->teacherSubjectGroups()->with('group','matiere')->get();
            

            $studentes = Student::orderBy('created_at', 'desc')->withTrashed()->get();
            $groups = group::whereIn("id", TeacherHasMatier::where("teacher_id", $teacher->id)->pluck("group_id"))->withTrashed()->get();
           // $matieres = Matiere::whereIn("id", TeacherHasMatier::all()->pluck("matier_id"))->get();
            $matieres = Matiere::whereIn("id", TeacherHasMatier::where("teacher_id", $teacher->id)->pluck("matier_id"))->withTrashed()->get();
            $teachers = Teachers::whereIn("id", TeacherHasMatier::where("teacher_id", $teacher->id)->pluck("teacher_id"))->withTrashed()->get();
            $exams = Exame::whereIn("teacher_id", TeacherHasMatier::where("teacher_id", $teacher->id)->pluck("teacher_id"))->get();
           

            $locales= Locale::orderBy('created_at', 'desc')->withTrashed()->get();
           

            return Inertia::render('Exams/Exams', ['exams'=>$exams, 'locales'=> $locales, 'matieres'=>$matieres,
            'groupes'=>$groups,'teachers'=> $teachers,'studentes'=>$studentes]);
            
        }
            $studentes = Student::orderBy('created_at', 'desc')->withTrashed()->get();
            $groupes = group::orderBy('created_at', 'desc')->withTrashed()->get();
            $matieres = Matiere::orderBy('created_at', 'desc')->withTrashed()->get();
            $teachers = Teachers::orderBy('created_at', 'desc')->withTrashed()->get();
            $exams = Exame::orderBy('created_at', 'desc')->get();
            $locales= Locale::orderBy('created_at', 'desc')->withTrashed()->get();
            return Inertia::render('Exams/Exams', ['exams'=>$exams, 'locales'=> $locales, 'matieres'=>$matieres, 'groupes'=>$groupes,'teachers'=> $teachers]);
        
      

       
    }

    
    public function getMatierByGroupname($id)
    {
        $userId = Auth::id();
        $matiers = User::find($userId)->teacher->teacherSubjectGroups()->where('group_id', $id)->with('matiere')->get();
      
       // log::debug("value student :",[$matiers]);
        return response()->json(['matiers' => $matiers]);
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
        try {
            // Your existing code here...
    
            Exame::create($request->validate([
                'date' => ['required'],
                'matier_id' => ['required'],
                'group_id' => ['required'],
                'locale_id' => ['required'],
                'teacher_id' => ['required'],
                
            ]));
            // Exame::create($request->all());
        } catch (\Exception $e) {
            // Log the exception for further investigation
           
    
            // Rethrow the exception for Laravel to handle
            throw $e;
        }
       
   
    }
    public function getNoteStudent ($id){
         $notes = Note::where('exam_id',$id)->get();
        if( $notes){
            return response()->json(['notes'=>$notes]);
        }
          
    }

    public function getStudent($id){
        $students = Student::where('class_id',$id)->get();
        // log::debug("value student :",[$students]);
        return response()->json(['students'=>$students]);
   }
      
    // public function storeNote(Request $request, $examId)
    // {
       
    //     $exam = Exame::findOrFail($examId);

    //     foreach($request->all() as $noteData) {
    //         $noteData['exam_id'] = $exam->id;
    //         Note::create($noteData);
    //     };
        

    // }
    public function storeNote(Request $request, $examId)
    {
        $exam = Exame::findOrFail($examId);
    
        foreach ($request->all() as $noteData) {
            $noteData['status'] = $noteData['status'] ?? null; //
            $noteData['observation'] = $noteData['observation'] ?? null; 
    
            // Find an existing note with the same data
            $existingNote = Note::where([
                'exam_id' => $exam->id,
                'student_id' => $noteData['student_id'],
                // Add other fields to compare as needed
            ])->first();
           
            if ($existingNote) {
                $existingNote->update($noteData);
                
            } else {
                $noteData['exam_id'] = $exam->id;
                Note::create($noteData);
              
            }
        }
    }
   
    
    /**
     * Display the specified resource.
     */
    public function show(Exame $exame)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exame $exame)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $exame = Exame::find($id);
        $exame->update([
            "date" => $request->date,
            "group_id" => $request->group_id,
            "locale_id" => $request->locale_id,
            "matier_id" => $request->matier_id,
            "teacher_id" => $request->teacher_id,
          
            
       
        ]);
    }

   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $exam = Exame::findOrFail($id)->delete();
    }
    
}
