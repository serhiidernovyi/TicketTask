<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketClassifyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable AI classification for tests
        config(['openai.classify_enabled' => false]);
    }

    public function test_classify_ticket_dispatches_job(): void
    {
        // GIVEN
        $ticket = Ticket::factory()->create([
            'subject' => 'Test bug report',
            'body' => 'Application crashes when clicking button',
            'category' => null,
        ]);

        // WHEN
        $response = $this->postJson("/api/tickets/{$ticket->id}/classify");

        // THEN
        $response->assertStatus(202)
            ->assertJson([
                'message' => 'Ticket classification job dispatched successfully'
            ]);
    }

    public function test_classify_ticket_returns_404_for_nonexistent_ticket(): void
    {
        // GIVEN
        // Non-existent ticket ID

        // WHEN
        $response = $this->postJson('/api/tickets/nonexistent-id/classify');

        // THEN
        $response->assertStatus(404);
    }

    public function test_classify_ticket_respects_rate_limiting(): void
    {
        // GIVEN
        $ticket = Ticket::factory()->create([
            'subject' => 'Test ticket',
            'body' => 'Test body',
        ]);

        // WHEN
        for ($i = 0; $i < 11; $i++) {
            $response = $this->postJson("/api/tickets/{$ticket->id}/classify");

            // THEN
            if ($i < 10) {
                $response->assertStatus(202);
            } else {
                $response->assertStatus(429);
            }
        }
    }

    public function test_classify_ticket_accepts_valid_ticket_id(): void
    {
        // GIVEN
        $ticket = Ticket::factory()->create([
            'subject' => 'Bug in login',
            'body' => 'Cannot login with valid credentials',
            'category' => null,
        ]);

        // WHEN
        $response = $this->postJson("/api/tickets/{$ticket->id}/classify");

        // THEN
        $response->assertStatus(202)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_classify_ticket_works_with_existing_category(): void
    {
        // GIVEN
        $ticket = Ticket::factory()->create([
            'subject' => 'Feature request',
            'body' => 'Please add dark mode',
            'category' => 'feature',
            'category_is_manual' => true,
        ]);

        // WHEN
        $response = $this->postJson("/api/tickets/{$ticket->id}/classify");

        // THEN
        $response->assertStatus(202)
            ->assertJson([
                'message' => 'Ticket classification job dispatched successfully'
            ]);
    }

    public function test_classify_ticket_works_with_unclassified_ticket(): void
    {
        // GIVEN
        $ticket = Ticket::factory()->create([
            'subject' => 'Question about API',
            'body' => 'How to use the REST API?',
            'category' => null,
        ]);

        // WHEN
        $response = $this->postJson("/api/tickets/{$ticket->id}/classify");

        // THEN
        $response->assertStatus(202)
            ->assertJson([
                'message' => 'Ticket classification job dispatched successfully'
            ]);
    }

    public function test_classify_ticket_returns_json_response(): void
    {
        // GIVEN
        $ticket = Ticket::factory()->create([
            'subject' => 'Test ticket',
            'body' => 'Test body',
        ]);

        // WHEN
        $response = $this->postJson("/api/tickets/{$ticket->id}/classify");

        // THEN
        $response->assertStatus(202)
            ->assertHeader('content-type', 'application/json')
            ->assertJsonStructure([
                'message'
            ]);
    }
}
