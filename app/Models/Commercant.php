<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commercant extends Model
{
    use HasFactory;

    protected $fillable = [
        'ninea',
        'adresse',
        'nin',
        'genre',
        'date_naiss',
        'numero_tel'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
