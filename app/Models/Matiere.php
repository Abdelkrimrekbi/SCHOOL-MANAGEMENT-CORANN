<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matiere extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'matieres';

    protected $fillable = [
        'name',
        'promotion',
        'level_id',
    ];

    public function teacherSubjectGroups()
    {
        return $this->hasMany(TeacherHasMatier::class, 'matiere_id');
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function Exame()
    {
        return $this->hasMany(Exame::class, 'matier_id');
    }

   
}
