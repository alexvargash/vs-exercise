<?php

namespace App\Console\Commands;

use App\User;
use Faker\Generator as Faker;
use Illuminate\Console\Command;

class UpdateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all the users\' firstname and timezone';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Faker $faker)
    {
        User::all()->each(function($user) use ($faker) {
            $user->update([
                'firstname' => $faker->firstName,
                'timezone' => $faker->randomElement(['CET', 'CST', 'GMT+1']),
            ]);
        });

        $this->info('All users are updated.');
    }
}
