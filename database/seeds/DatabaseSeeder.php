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
        $this->call(AgegroupsTableSeeder::class);
        $this->call(CompetitionlevelsTableSeeder::class);
        $this->call(SeasonsTableSeeder::class);
        $this->call(AssociationsTableSeeder::class);
        $this->call(CompetitionsTableSeeder::class);
        $this->call(PhasesTableSeeder::class);
        $this->call(RoundsTableSeeder::class);
        $this->call(ClubsTableSeeder::class);
    }
}
