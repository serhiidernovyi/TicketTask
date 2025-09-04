<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Ticket;

class TicketUpdateTest extends TestCase
{
    use DatabaseTransactions;

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
}
