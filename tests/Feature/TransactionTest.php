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
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_can_create_transaction(User $user)
    {
        $users = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/transaction', [
            'user_id' => $users->id,
            'amount' => 50.00,
            'type' => 'deposit',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('transactions', ['user_id' => $users->id, 'amount' => 50.00]);
    }

    public function test_balance_cannot_go_negative(User $user)
    {
        $users = User::factory()->create();
        $balance = Balance::create(['user_id' => $users->id, 'balance' => 20.00]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/transaction', [
            'user_id' => $users->id,
            'amount' => 50.00,
            'type' => 'withdrawal',
        ]);

        $response->assertStatus(400);
        $this->assertEquals(20.00, $balance->fresh()->balance);
    }

}