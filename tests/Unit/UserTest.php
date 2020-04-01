<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function to_create_a_user_timezone_can_not_be_null()
    {
        try {
            User::create([
                'firstname' => 'John',
                'email' => 'test@example.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertContains('NOT NULL constraint failed: users.timezone', $e->errorInfo);
        }
    }

    /** @test */
    public function to_create_a_user_email_must_be_unique()
    {
        try {
            factory(User::class)->create(['email' => 'test@example.com']);
            factory(User::class)->create(['email' => 'test@example.com']);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertContains('UNIQUE constraint failed: users.email', $e->errorInfo);
        }
    }
}

