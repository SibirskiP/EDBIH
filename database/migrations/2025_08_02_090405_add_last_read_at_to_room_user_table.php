<?php
// a_nova_migracija_za_room_user_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastReadAtToRoomUserTable extends Migration
{
    public function up()
    {
        Schema::table('room_user', function (Blueprint $table) {
            // Dodaje kolonu koja prati kada je korisnik zadnji put bio aktivan u sobi
            $table->timestamp('last_read_at')->nullable()->after('is_admin');
        });
    }

    public function down()
    {
        Schema::table('room_user', function (Blueprint $table) {
            $table->dropColumn('last_read_at');
        });
    }
}
