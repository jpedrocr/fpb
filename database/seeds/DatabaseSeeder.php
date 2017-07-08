<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DeleteAllSeeder::class);

        $this->call(CategoriesTableSeeder::class);
        $this->call(GendersTableSeeder::class);
        $this->call(AgeGroupsTableSeeder::class);
        $this->call(CompetitionLevelsTableSeeder::class);
        $this->call(SeasonsTableSeeder::class);
        $this->call(AssociationsTableSeeder::class);
    }
}
