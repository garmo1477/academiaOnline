<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //eliminamos el directorio categories de Storage
        Storage::deleteDirectory('categories');
        //creamos el directorio de categories de Storage
        Storage::makeDirectory('categories');
        $this->call(CategorySeeder::class);

        //llamada a UserSeeder, que es el q crea los 20 usuarios
        $this->call(UserSeeder::class);

        //para crear los cursos
        Storage::deleteDirectory('courses');
        Storage::makeDirectory('courses');
        $this->call(CourseSeeder::class);

        Storage::deleteDirectory('units');
        Storage::makeDirectory('units');
    }
}
