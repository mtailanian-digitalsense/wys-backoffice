<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed('CountriesTableSeeder');
    }

    /** @test */
    public function it_returns_user_on_valid_registration()
    {
        $data = [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'last_name' => 'test',
            'country_id' => '1',
        ];

        $response = $this->postJson('/api/v1/user/register', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status_code' => '2001'
            ]);

    }

    /** @test */
    public function it_returns_field_required_validation_errors_on_invalid_registration()
    {
        $data = [];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'username' => ['field is required.'],
                    'email' => ['field is required.'],
                    'last_name' => ['field is required'],
                    'password' => ['field is required.'],
                ]
            ]);
    }
}
