<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Panier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    public function produits() {
        return $this->belongsToMany(Produit::class, 'panier_produits')->withPivot('quantite');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
