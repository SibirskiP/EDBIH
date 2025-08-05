<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obavijesti', function (Blueprint $table) {

            $table->id();
            $table->foreignId('korisnik_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('naslov');
            $table->text('sadrzaj');
            $table->boolean('procitano')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obavijesti');
    }
};
