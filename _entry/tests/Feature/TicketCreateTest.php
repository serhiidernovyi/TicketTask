<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TicketCreateTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_create_ticket(): void
    {
        //GIVEN
        $ticketData = [
            'subject' => 'Test Ticket Subject',
            'body' => 'This is a test ticket body content.',
            'status' => 'open'
        ];

        //WHEN
        $response = $this->postJson('/api/tickets', $ticketData);

        //THEN
        $response->assertStatus(201)
            ->assertJson([
                    'subject' => 'Test Ticket Subject',
                    'body' => 'This is a test ticket body content.',
                    'status' => 'open'
            ]);

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Test Ticket Subject',
            'body' => 'This is a test ticket body content.',
            'status' => 'open'
        ]);
    }
}
