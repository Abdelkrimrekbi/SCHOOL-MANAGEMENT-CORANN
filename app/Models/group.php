<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class group extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'groups';

    protected $fillable = [
        'group',
        'nbr_students',
        'promotion',
        'level_id',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
    public function teacherSubjectGroups()
    {
        return $this->hasMany(TeacherHasMatier::class, 'group_id');
    }
    public function student()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
    public function absence()
    {
        return $this->hasMany(Absence::class, 'class_id');
    }
}
