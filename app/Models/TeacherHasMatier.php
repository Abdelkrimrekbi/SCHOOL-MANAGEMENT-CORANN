<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherHasMatier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teacher_has_matiers';

    protected $fillable = [
        'promotion',    
        'teacher_id',
        'group_id',
        'matier_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teachers::class, 'teacher_id');
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'matier_id'); 
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    
    public function seance()
    {
        return $this->hasMany(Seance::class, 'teacherHasMatier_id');
    }
}
