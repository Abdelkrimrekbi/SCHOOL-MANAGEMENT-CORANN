<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\group;
use App\Models\Note;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PrintInformation extends Controller
{
    
     public function index()
     { 
        $note =  Note::orderBy('created_at', 'desc')->get();
    $absence = Absence::orderBy('created_at', 'desc')->get();
    $group = group::orderBy('created_at', 'asc')->get();
    //log::debug("user :",[$absence]); 
     return Inertia::render('PrintInformation/PrintInformation', ['note'=> $note,'absence' => $absence,'group'=>$group]);
      
     }
}
