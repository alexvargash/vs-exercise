<?php

namespace Tests\Feature\Commands;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function update_users_command_should_update_all_users()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->artisan('update:users')
            ->expectsOutput('All users are updated.')
            ->assertExitCode(0);

        $updatedUser1 = User::find(1);
        $updatedUser2 = User::find(2);

        $this->assertNotEquals($user1->toArray(), $updatedUser1->toArray());
        $this->assertNotEquals($user2->toArray(), $updatedUser2->toArray());
    }
}
