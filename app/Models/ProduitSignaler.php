<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProduitSignaler extends Model
{
    use HasFactory;

    protected $fillable = [
        'motif',
        'produit_id',
        'client_id'
    ];
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
