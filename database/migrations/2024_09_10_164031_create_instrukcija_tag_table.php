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
        Schema::create('instrukcija_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instrukcija_id');
            $table->unsignedBigInteger('tag_id');
            $table->foreign('instrukcija_id')->references('id')->on('instrukcije');
            $table->foreign('tag_id')->references('id')->on('tagovi');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrukcija_tag');
    }
};
