<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Instrukcija;
use App\Models\Komentar;
use App\Models\Materijal;
use App\Models\Objava;
use App\Models\Odgovor;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory(30)->create([]);
        Instrukcija::factory(20)->create();
        Objava::factory(20)->create();
        Objava::factory(20)->create();
//        Materijal::factory(20)->create();
        Komentar::factory(20)->create();
        Odgovor::factory(20)->create();




    }
}
