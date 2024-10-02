<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ListdattenController extends Controller
{
    public function index()
    {
         $students =  Student::where('isChecked', 0)->orderBy('created_at', 'asc')->with(['level', 'group'])->paginate(6);
        $levels = Level::orderBy('created_at', 'desc')->get();
       
     
        return Inertia::render('Listdatten/Listdatten',['students' => $students , 'levels' => $levels]);
    }
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

        log::debug("isChecked :",[$request["isChecked"]]);
    
        // Update the student record with the request data
        $student->update($request->all());
       
    }

}








// $student = Student::find($id);

// $request["isChecked"] =  ($request["isChecked"] == true) ? 1 : 0;

// // Check if the student exists
// if (!$student) {
//     return response()->json(['message' => 'Student not found'], 404);
// }

// log::debug("isChecked :",[$request["isChecked"]]);

// // Update the student record with the request data
// $student->update([...$request->only('date_birth', 'date_birth',
// 'name',
// 'profession',
// 'la_situation',
// 'lieu_birth',
// 'level_id',
// 'class_id',
// 'address',
// 'phone',
// 'niveau_etude',
// 'name_school',
// 'service_mensuel',
// 'droit_annuel'), 'isChecked' => ($request["isChecked"] == true) ? 1 : 0]);

// }