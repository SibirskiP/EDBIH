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


        Schema::create('users', function (Blueprint $table) {
            $lokacije = config('mojconfig.lokacije');
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('titula');
            $table->enum('lokacija',$lokacije);
            $table->string('kontakt');
            $table->string('opis');
            $table->string('profilna_slika')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
