<?php

use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Commande::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Produit::class)->constrained()->onDelete('cascade');
            $table->float('montant');
            $table->integer('nombre_produit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_commandes');
    }
};
