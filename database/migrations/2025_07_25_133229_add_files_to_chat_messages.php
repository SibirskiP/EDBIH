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
        Schema::table('chat_messages', function (Blueprint $table) {
            //

            $table->string('file_name')->nullable()->after('message');
            $table->string('file_name_original')->nullable()->after('file_name');
            $table->string('file_path')->nullable()->after('file_name_original');
            $table->string('file_type')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            //

            $table->dropColumn(['file_name', 'file_name_original', 'file_path', 'file_type']);

        });
    }
};
