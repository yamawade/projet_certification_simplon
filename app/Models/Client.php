<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'adresse',
        'numero_tel',
        'genre',
        'date_naiss'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
