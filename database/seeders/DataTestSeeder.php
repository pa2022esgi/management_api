<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DataTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::create([
            'email' => 'test@mail.com',
            'password' => bcrypt('Test1234'),
        ]);

        $user2 = User::create([
            'email' => 'user@mail.com',
            'password' => bcrypt('Test1234'),
        ]);

        $user3 = User::create([
            'email' => 'new@mail.com',
            'password' => bcrypt('Test1234'),
        ]);

        $project = Project::create([
            'name' => 'Test project',
            'description' => 'Test project description',
            'token' => 'TESTTOK',
            'created_by' => $user1->id,
        ]);

        $project->users()->attach($user1->id, ['banished' => false]);
        $project->users()->attach($user2->id, ['banished' => true]);
        $project->users()->attach($user3->id, ['banished' => false]);

        $project->labels()->createMany([
            [
                'name' => 'Test label 1',
                'color' => '#fcd444',
            ],
            [
                'name' => 'Test label 2',
                'color' => '#5bc0de',
            ],
            [
                'name' => 'Test label 3',
                'color' => '#8cc43c',
            ]
        ]);

        $card = Card::create([
            "title" => "Test card 1",
            "description" => "Test card description",
            "due_date" => "2020-01-01",
            "user_id" => $user1->id,
            "project_id" => $project->id,
            "status_id" => 1
        ]);

        $card->labels()->attach(3);

        $card2 = Card::create([
            "title" => "Test card 2",
            "description" => "Test card description",
            "due_date" => "2020-05-01",
            "user_id" => $user2->id,
            "project_id" => $project->id,
            "status_id" => 2
        ]);

        $card2->labels()->attach(2);
        $card2->labels()->attach(1);

        $card3 = Card::create([
            "title" => "Test card 3",
            "description" => "Test card description",
            "due_date" => "2020-08-01",
            "user_id" => $user3->id,
            "project_id" => $project->id,
            "status_id" => 3
        ]);

        $card3->labels()->attach(1);
    }
}
