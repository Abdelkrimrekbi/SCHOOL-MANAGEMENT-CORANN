<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'levels';

    protected $fillable = [
        'level',
        'price',
        'description'

    ];

    public function groups()
    {
        return $this->hasMany(Group::class, 'level_id');
    }

    public function matieres()
    {
        return $this->hasMany(Matiere::class, 'level_id');
    }
}
