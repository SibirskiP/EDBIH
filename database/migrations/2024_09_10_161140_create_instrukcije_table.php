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


        Schema::create('instrukcije', function (Blueprint $table) {
            $lokacije = config('mojconfig.lokacije');
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->enum('kategorija', ['osnovna skola', 'srednja skola', 'fakultet', 'jezici', 'ostalo']);
            $table->enum('vrsta', ['uzivo', 'online', 'uzivo i online']);
            $table->enum('lokacija',$lokacije);

            $table->string('naziv');
            $table->decimal('cijena',5,2);
            $table->string('opis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrukcije');
    }
};
