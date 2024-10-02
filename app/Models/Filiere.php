<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filieres';

    protected $fillable = [
        'filiere',
       // 'departement_id',
        'level_id',
    ];

    // public function departement()
    // {
    //     return $this->belongsTo(Departement::class, 'departement_id');
    // }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
