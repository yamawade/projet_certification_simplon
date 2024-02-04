<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feddback extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'email',
        'message',
        'numero_tel'
    ];
}
