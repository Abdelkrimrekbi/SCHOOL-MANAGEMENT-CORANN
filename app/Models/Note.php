<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';

    protected $fillable = [
        'lhizb',
        'tomon',
        'evaluation',
        'description',
        'promotion',
        'seance_id',
        'student_id',
    ];
    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class, 'seance_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
