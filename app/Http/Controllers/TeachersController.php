<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\group;
use App\Models\Locale;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Roles;
use App\Models\Seance;
use App\Models\TeacherHasMatier;
use App\Models\Teachers;
use App\Models\User;
use App\Models\TeacherHasMatierController;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;


class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
       $teachers = Teachers::orderBy('created_at', 'desc')->get();
        $user= User::orderBy('created_at', 'desc')->get();
        $matieres= Matiere::orderBy('created_at', 'desc')->get();
        $teacherHasMatiere= TeacherHasMatier::orderBy('created_at', 'desc')->get();
       return Inertia::render('Teachers/Teachers', ['teachers' => $teachers,'user' =>  $user,'matieres'=> $matieres,'teacherHasMatiere' => $teacherHasMatiere ]);
     
    }
    // public function indexProf()
    // { 
       
    //     $note =  Note::orderBy('created_at', 'desc')->get();
    //    $absence = Absence::orderBy('created_at', 'desc')->get();
    //    $group = group::orderBy('created_at', 'asc')->get();
    //    log::debug("user :",[$absence]); 
    //     return Inertia::render('Teachers/IndexTeacher', ['note'=> $note,'absence' => $absence,'group'=>$group]);
     
    // }
    

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
        // Validate the request data
        $validatedData = $request->validate([
            'teachers.CIN'       => ['required'],
            'teachers.name'      => ['required'],
            'teachers.email'     => ['required', 'email'],
            'teachers.phone'     => ['nullable'],
            'teachers.address'   => ['required'],
            'user.password'      => ['required'],
            'user.name'          => ['required'],
            'user.email'         => ['required', 'email'],
        ]);
    
        // Create the user
        $user = User::create([
            'name'     => $validatedData['user']['name'],
            'email'    => $validatedData['user']['email'],
            'password' => Hash::make($validatedData['user']['password']),
            $role = 'teacher',
            'role' =>  $role
        ]);
    
        // Assign the "teacher" role to the user
        $role = 'teacher';
        if (Roles::where('name', $role)->exists()) {
            $user->assignRole($role);
        } else {
            Log::debug("Role {$role} does not exist.");
        }
    
        // Prepare teacher data and assign user_id
        $teachersData = $validatedData['teachers'];
        $teachersData['user_id'] = $user->id;
    
        // Create the teacher
        Teachers::create($teachersData);
    
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(Teachers $teachers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teachers $teachers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {   
        $teachers = teachers::find($id);
        $teachers->update([
            "CIN" => $request->CIN,
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            // "amount" => $request->amount,
            "address" => $request->address,
          
        ]);
    }
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $teachers = Teachers::findOrFail($id)->delete();
    }
       
    }

