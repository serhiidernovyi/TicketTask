<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Ticket;

class TicketUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_ticket(): void
    {
        //GIVEN
        $ticket = Ticket::create([
            'subject' => 'Original Subject',
            'body' => 'Original body content.',
            'status' => 'new'
        ]);

        $updateData = [
            'subject' => 'Updated Subject',
            'body' => 'Updated body content.',
            'status' => 'open'
        ];

        //WHEN
        $response = $this->putJson("/api/tickets/{$ticket->id}", $updateData);

        //THEN
        $response->assertStatus(200)
            ->assertJson([
                    'id' => $ticket->id,
                    'subject' => 'Updated Subject',
                    'body' => 'Updated body content.',
                    'status' => 'open'
            ]);
        $this->assertDatabaseCount('tickets', 1);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'subject' => 'Updated Subject',
            'body' => 'Updated body content.',
            'status' => 'open'
        ]);
    }

    public function test_manual_category_change_sets_category_is_manual_true(): void
    {
        //GIVEN
        $ticket = Ticket::create([
            'subject' => 'Test ticket',
            'body' => 'Test body',
            'status' => 'new',
            'category' => 'bug',
            'category_is_manual' => false
        ]);

        $updateData = [
            'category' => 'feature'
        ];

        //WHEN
        $response = $this->putJson("/api/tickets/{$ticket->id}", $updateData);

        //THEN
        $response->assertStatus(200);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'category' => 'feature',
            'category_is_manual' => true
        ]);
        
        $updatedTicket = Ticket::find($ticket->id);
        $this->assertNotNull($updatedTicket->category_changed_at);
    }

    public function test_manual_category_change_from_null_sets_category_is_manual_true(): void
    {
        //GIVEN
        $ticket = Ticket::create([
            'subject' => 'Test ticket',
            'body' => 'Test body',
            'status' => 'new',
            'category' => null,
            'category_is_manual' => false
        ]);

        $updateData = [
            'category' => 'question'
        ];

        //WHEN
        $response = $this->putJson("/api/tickets/{$ticket->id}", $updateData);

        //THEN
        $response->assertStatus(200);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'category' => 'question',
            'category_is_manual' => true
        ]);
    }

    public function test_no_category_change_does_not_affect_category_is_manual(): void
    {
        //GIVEN
        $ticket = Ticket::create([
            'subject' => 'Test ticket',
            'body' => 'Test body',
            'status' => 'new',
            'category' => 'bug',
            'category_is_manual' => false
        ]);

        $updateData = [
            'subject' => 'Updated subject',
            'category' => 'bug' // Same category
        ];

        //WHEN
        $response = $this->putJson("/api/tickets/{$ticket->id}", $updateData);

        //THEN
        $response->assertStatus(200);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'category' => 'bug',
            'category_is_manual' => false // Should remain false
        ]);
    }

    public function test_manual_category_change_preserves_existing_manual_flag(): void
    {
        //GIVEN
        $ticket = Ticket::create([
            'subject' => 'Test ticket',
            'body' => 'Test body',
            'status' => 'new',
            'category' => 'bug',
            'category_is_manual' => true
        ]);

        $updateData = [
            'category' => 'feature'
        ];

        //WHEN
        $response = $this->putJson("/api/tickets/{$ticket->id}", $updateData);

        //THEN
        $response->assertStatus(200);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'category' => 'feature',
            'category_is_manual' => true // Should remain true
        ]);
    }
}
