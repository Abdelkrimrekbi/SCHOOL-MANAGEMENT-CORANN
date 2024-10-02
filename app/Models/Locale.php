<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locale extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'locales';

    protected $fillable = [
        'locale',
        'type',
        'capacity',
       
    ];

    public function seance()
    {
        return  $this->hasMany(Seance::class, 'local_id');
        }
        
    public function exam()
    {
        return $this->hasOne(Exame::class, 'locale_id');
    }
}
