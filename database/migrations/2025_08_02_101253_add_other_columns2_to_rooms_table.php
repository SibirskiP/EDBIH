<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            //
            $table->string('opis')->nullable();
            $table->enum('kategorija', ['osnovna skola', 'srednja skola', 'fakultet', 'jezici', 'ostalo']);
            $table->enum('privatnost', ['otvorena', 'privatna']);
            $table->string('profilna_slika')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            //
        });
    }
};
