<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exame extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exames';

    protected $fillable = [
        'date',
        'group_id',
        'matier_id',
        'locale_id',
        'teacher_id',
        
    ];
    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'matier_id');
    }
   
    public function group()
    {
        return $this->belongsTo(group::class, 'group_id');
    }
    public function locale()
    {
        return $this->belongsTo(Locale::class, 'locale_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teachers::class, 'teacher_id');
    }
 
 
    // Define hasMany, hasOne, and belongsToMany relationships

    // Example of hasMany relationship

    // public function students()
    // {
    //     return $this->hasMany(Student::class);
    // }

    // // Example of hasOne relationship
    // public function examResult()
    // {
    //     return $this->hasOne(Exame::class);
    // }


}
