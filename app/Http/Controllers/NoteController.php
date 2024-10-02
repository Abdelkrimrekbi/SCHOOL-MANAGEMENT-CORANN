<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $configuration = Configuration::orderBy('created_at', 'desc')->first();

        if (!$configuration) {
            return response()->json(['error' => 'Configuration not found'], 404);
        }
        $validatedData = $request->validate([
            'lhizb' => ['required'],
            'tomon' => ['required'],
            'evaluation' => ['required'],
            'description' => [''],
            'seance_id'=> ['required'],
            'student_id'=> ['required'],
        ]);
       // $validatedData['id_users'] = $utilisateur;
        $validatedData['promotion'] = $configuration->promotion;
        Note::create($validatedData);
        
        
    }

    public function getNotebyStudentbySence($studentId, $seanceId)
    {
       
        $notes=Note::where(['student_id' => $studentId, 'seance_id' => $seanceId])->get();
        $notesAvant = Note::where('student_id', $studentId)
                ->orderBy('created_at', 'desc') 
                ->limit(1) 
                ->get();
                //log::debug("value student :",[$notesAvant]);
        return response()->json(['notes' => $notes,'notesAvant'=>$notesAvant]);
    }



    public function getNotebybySence($studentId, $seanceId)
    {
        $notesSeance=Note::where(['student_id' => $studentId, 'seance_id' => $seanceId])->get();
        return response()->json(['notesSeance' => $notesSeance]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {  
        //log::debug("value request :",[$request]);
       // log::debug("value request :",[$id]);
      
        $note = Note::find($id);
        $note->update([
            "lhizb" => $request->lhizb,
            "tomon" => $request->tomon,
            "evaluation" => $request->evaluation,
            "description" => $request->description,
            "seance_id" => $request->seance_id,
            "student_id" => $request->student_id,
        ]);
    }
   
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        //
    }
}
