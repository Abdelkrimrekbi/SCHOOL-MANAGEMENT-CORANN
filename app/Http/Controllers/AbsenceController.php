<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Locale;
use App\Models\Seance;
use App\Models\TeacherHasMatier;
use App\Models\User;
use App\Models\Configuration;
use App\Models\group;
use App\Models\Note;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Collection;
use Mpdf\Mpdf;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $user = User::find($userId);
        log::debug("user 1 :",[$user->teacher]); 
        if ($user && $user->teacher) {
            log::debug("teacher :");
            $teacher = $user->teacher->teacherSubjectGroups()->with('group.teacherSubjectGroups.seance')->distinct()->get();
            log::debug("teacher :",[$teacher]);
          
        } else {
            $teacher = collect();
             log::debug("absence1 :",[$teacher]); 
        }
        
        $local = Locale::orderBy('created_at', 'desc')->get();
        
        return Inertia::render('Absence/Absence', ['teacher' => $teacher, 'local' => $local]);
    }

    public function getMatierByGroup($id)
    {
        $userId = Auth::id();
        $matiers = User::find($userId)->teacher->teacherSubjectGroups()->where('group_id', $id)->with('matiere', 'seance')->get();
        return response()->json(['matiers' => $matiers]);
    }

    public function getSeancesByGroup($groupId)
    {  //  log::debug("valuegroup id :",[$groupId]);
       
        $userId = Auth::id();
       // log::debug("value user id ",[$userId]);
        $teacherSubjectGroups = User::find($userId)->teacher
            ->teacherSubjectGroups()
            ->where(['group_id' => $groupId])
            ->with(['seance' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->get();
        $seances = $teacherSubjectGroups->pluck('seance')->collapse();
        $teacherHasMatierId = $teacherSubjectGroups[0]->id;
       //log::debug("value  ::::",[$seances]);
        return response()->json(['seance' => $seances, 'teacherHasMatier_id' => $teacherHasMatierId]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    

    public function store(Request $request)
    {
        log::debug("All :",$request->all());
        $absenceData = $request->absences;
        
        $day = Absence::where('group_id', $absenceData['group_id'])
                      ->where('seance_id', $absenceData['seance_id'])
                      ->first();
    
        if ($day) {
            $listAbsences = $day->list_Absence;
    
            foreach ($absenceData['list_Absence'] as $absence) {
                $studentId = $absence['id'];
                $studentExists = false;
    
                foreach ($listAbsences as $index => &$item) {
                    if ($item['id'] == $studentId) {
                        $studentExists = true;
                        // Student exists in the stored data, so delete it
                        unset($listAbsences[$index]);
                        break;
                    }
                }
    
                if (!$studentExists) {
                    // Student doesn't exist, add it to the list of absences
                    $listAbsences[] = $absence;
                }
            }
    
            $day->list_Absence = array_values($listAbsences);
            $day->save();
        } else {
            $validatedData = $request->validate([
                'absences.list_Absence' => ['required'],
                'absences.group_id' => ['required'],
                'absences.seance_id' => ['required'],
                'absences.date' => ['required'],
            ]);
    
            Absence::create($validatedData['absences']);
        }
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show($seance)
    {
        //with('group')->
        $absences = Absence::where('seance_id',$seance)->get();
        // log::debug("seanceseance :",[$absences]);
    }


    public function absencesByMonth($groupId, $date)
    {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fr_FR', 'fr', 'fre', 'fra');
        $timestamp = strtotime($date);
        $mois = strftime('%B', $timestamp);
    
        $configuration = Configuration::first();
        $group = Group::where('id', $groupId)->with('level')->first();
    
        $listAbsence = new Absence();
        $absencesForGroup = $listAbsence->absencesByMonth($groupId, $date);
    
        $path = public_path("storage/" . $configuration->logo);
        $image = null;
    
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $image = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));
        }
    
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'default_font' => 'Arial',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'format' => 'A1'
        ]);
    
        $html = view('list_absence', compact('configuration', 'group', 'absencesForGroup', 'image', 'mois'))->render();
    
        $mpdf->WriteHTML($html);
        return $mpdf->Output('list_absence.pdf', 'D');
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function getStudentNotesPDF($groupId)
    {
    
        $configuration = Configuration::first();
        $group = Group::where('id', $groupId)->with('level')->first();
        Log::debug("group :", [$group]);
    
        $notesForGroup = $this->getStudentNotes($groupId);
    
        if ($notesForGroup instanceof Collection) {
            $notesForGroup = $notesForGroup->toArray();
        }
        Log::debug("notesForGroup :", [$notesForGroup]);
    
        $path = public_path("storage/" . $configuration->logo);
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $image = file_get_contents($path);
    
            if ($image) {
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image);
            } else {
                $base64 = null;
            }
            Log::debug("configuration :", [$configuration]);
            Log::debug("group :", [$group]);
            Log::debug("notesForGroup :", [$notesForGroup]);
    
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'default_font' => 'arial', 
                'autoScriptToLang' => true, 
                'autoLangToFont' => true, 
                'direction' => 'rtl',
                 'format' => 'A4'
            ]);
        
            // Load view content
            $html = view('list_notes', [
                'configuration' => $configuration,
                'group' => $group,
                'notesForGroup' => $notesForGroup,
                'image' => $base64,
            ])->render();
        
            // Write the content to the PDF
            $mpdf->WriteHTML($html);
        
            // Output the PDF as a download
            return $mpdf->Output('list_notes.pdf', 'D');
        }
    }
    
     public function getStudentNotes($groupId)
{
    $studentsInGroup = Student::where('class_id', $groupId)->get();
    Log::debug("studentsInGroup: ", [$studentsInGroup]);

    // Initialize the note data structure for each student
    $notesGroupedByStudent = $studentsInGroup->mapWithKeys(function ($student) {
        // Retrieve the first and last notes for each student
        $firstNote = Note::where('student_id', $student->id)->orderBy('created_at', 'asc')->first();
        $lastNote = Note::where('student_id', $student->id)->orderBy('created_at', 'desc')->first();

        $noteData = [
            'id' => $student->id,
            'name' => $student->name,
            'first_note' => $firstNote ? $firstNote->lhizb : null,
            'final_note' => $lastNote ? $lastNote->lhizb : null,
        ];
        return [$student->id => $noteData];
    });

    Log::debug("NotesGroupedByStudent: ", [$notesGroupedByStudent]);

    return $notesGroupedByStudent;
}
    public function edit(Absence $absence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absence $absence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($student_id, $seance_id)
    {
        Absence::where(['student_id' => $student_id, 'seance_id' => $seance_id])->delete();
    }
}
