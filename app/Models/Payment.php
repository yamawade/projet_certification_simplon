<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Commande;
use App\Models\DetailCommande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'commande_id',
        'amount',
        'token',
    ];

    protected $table = 'payments';

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

}