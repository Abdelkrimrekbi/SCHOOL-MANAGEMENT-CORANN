<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'seances';

    protected $fillable = [
        // 'startSeance',
        // 'finSeance',
        // 'local_id',
        // 'type',
        'teacherHasMatier_id'
    ];

    public function teacherSubjectGroups(): BelongsTo
    {
        return $this->belongsTo(TeacherHasMatier::class, 'teacherHasMatier_id');
    }

    // public function local(): BelongsTo
    // {
    //     return $this->belongsTo(Locale::class, 'local_id');
    // }

    public function absence()
    {
        return $this->hasMany(Absence::class, 'seance_id');
    }
}
