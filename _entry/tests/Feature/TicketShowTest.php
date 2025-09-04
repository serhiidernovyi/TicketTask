<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Ticket;

class TicketShowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_show_single_ticket(): void
    {
        //GIVEN
        $ticket = Ticket::factory()->create([
            'subject' => 'Test Ticket Subject',
            'body' => 'Test ticket body content',
            'status' => 'open',
            'category' => 'bug',
            'note' => 'Internal note for this ticket'
        ]);

        //WHEN
        $response = $this->getJson("/api/tickets/{$ticket->id}");

        //THEN
        $response->assertStatus(200)
            ->assertJson([
                    'id' => $ticket->id,
                    'subject' => 'Test Ticket Subject',
                    'body' => 'Test ticket body content',
                    'status' => 'open',
                    'category' => 'bug',
                    'note' => 'Internal note for this ticket'
            ]);
    }

    public function test_returns_404_for_nonexistent_ticket(): void
    {
        //GIVEN
        //WHEN
        $response = $this->getJson('/api/tickets/nonexistent-id');

        //THEN
        $response->assertStatus(404);
    }
}

