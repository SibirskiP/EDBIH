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
        Schema::create('materijali', function (Blueprint $table) {
            $table->id();
            $table->string('naziv');
            $table->string('opis')->nullable();
            $table->enum('kategorija', ['osnovna skola', 'srednja skola', 'fakultet', 'jezici', 'ostalo']);
            $table->string('putanja')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materijali');
    }
};
