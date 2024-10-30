<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Balance;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_user_can_login()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->postJson('/api/user-token', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_user_cannot_access_protected_routes_without_authentication()
    {
        $response = $this->getJson('/api/balance');

        $response->assertStatus(401); // Unauthorized
    }


    public function test_user_can_create_deposit_transaction()
    {
        // Create a user
        $user = User::factory()->create();

        $balance = Balance::create([
            'user_id' => $user->id,
            'balance' => 0.00
        ]);

        // Authenticate as the user
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/transaction', [
            'user_id' => $user->id,
            'amount' => 100.00,
            'type' => 'deposit',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'amount' => 100.00,
            'type' => 'deposit'
        ]);

        $this->assertEquals(100.00, $balance->fresh()->balance);
    }


    public function test_balance_cannot_go_negative()
    {
        $user = User::factory()->create();
        $balance = Balance::create(['user_id' => $user->id, 'balance' => 20.00]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/transaction', [
            'user_id' => $user->id,
            'amount' => 50.00,
            'type' => 'withdrawal',
        ]);

        $response->assertStatus(400);
        $this->assertEquals(20.00, $balance->fresh()->balance);
    }

    public function test_user_can_retrieve_balance()
    {
        $user = User::factory()->create();
        $balance = Balance::create(['user_id' => $user->id, 'balance' => 200.00]);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/balance');

        $response->assertStatus(200);
        $response->assertJson(['balance' => 200.00]);
    }
}