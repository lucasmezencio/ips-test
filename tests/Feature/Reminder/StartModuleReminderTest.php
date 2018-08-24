<?php

namespace Test\Feature\Reminder;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use URL;

/**
 * Class StartModuleReminderTest
 *
 * @package Test\Feature\Reminder
 */
class StartModuleReminderTest extends TestCase
{
    use RefreshDatabase;

    private $email = 'lucas@test.com';

    /** @test */
    public function itMustBeAnEndpoint(): void
    {
        $response = $this->json('GET', URL::route('api.module_reminder_assigner', [
            'email' => $this->email,
        ]));
        $statusCode = $response->getStatusCode();

        $this->assertNotSame($statusCode, 404);
    }

    /** @test */
    public function itMustAcceptOnlyPost(): void
    {
        $route = URL::route('api.module_reminder_assigner', [
            'email' => $this->email,
        ]);
        $response = $this->json('GET', $route);
        $response->assertStatus(405);
    }

    /** @test */
    public function itShouldSuccess(): void
    {
        $route = URL::route('api.module_reminder_assigner', [
            'email' => $this->email,
        ]);

        $response = $this->json('POST', $route);
        $response->assertStatus(200);
    }

    /** @test */
    public function itMustHaveAnEmail(): void
    {
        $route = URL::route('api.module_reminder_assigner', [
            'email' => null,
        ]);
        $response = $this->json('POST', $route);
        $response->assertStatus(404);
    }

    /** @test */
    public function itShouldReturnJson(): void
    {
        $route = URL::route('api.module_reminder_assigner', [
            'email' => $this->email,
        ]);
        $response = $this->json('POST', $route);
        $response->assertJsonStructure([
            'status',
            'message',
        ]);
    }

    /** @test */
    public function itShouldReturnStatusFalseWhenNoEmailFound(): void
    {
        $route = URL::route('api.module_reminder_assigner', [
            'email' => 'no@test.com',
        ]);
        $response = $this->json('POST', $route);
        $response->assertJson([
            'status' => false,
            'message' => 'No query results for model [App\\User].',
        ]);
    }
}
