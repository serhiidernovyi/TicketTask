<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Ticket;

class TicketListTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_list_tickets(): void
    {
        //GIVEN
        $ticket1 = Ticket::factory()->create([
            'subject' => 'First Ticket',
            'body' => 'First ticket body',
            'status' => 'open'
        ]);

        $ticket2 = Ticket::factory()->create([
            'subject' => 'Second Ticket',
            'body' => 'Second ticket body',
            'status' => 'new'
        ]);

        $ticket3 = Ticket::factory()->create([
            'subject' => 'Third Ticket',
            'body' => 'Third ticket body',
            'status' => 'closed'
        ]);

        //WHEN
        $response = $this->getJson('/api/tickets');

        //THEN
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [
                    [
                        'id' => $ticket1->id,
                        'subject' => 'First Ticket',
                        'body' => 'First ticket body',
                        'status' => 'open'
                    ],
                    [
                        'id' => $ticket2->id,
                        'subject' => 'Second Ticket',
                        'body' => 'Second ticket body',
                        'status' => 'new'
                    ],
                    [
                        'id' => $ticket3->id,
                        'subject' => 'Third Ticket',
                        'body' => 'Third ticket body',
                        'status' => 'closed'
                    ]
                ]
            ]);
    }

    public function test_can_filter_tickets_by_status(): void
    {
        //GIVEN
        Ticket::factory()->create([
            'subject' => 'Open Ticket 1',
            'body' => 'This is open',
            'status' => 'open'
        ]);

        Ticket::factory()->create([
            'subject' => 'New Ticket 1',
            'body' => 'This is new',
            'status' => 'new'
        ]);

        Ticket::factory()->create([
            'subject' => 'Open Ticket 2',
            'body' => 'This is also open',
            'status' => 'open'
        ]);

        Ticket::factory()->create([
            'subject' => 'Closed Ticket 1',
            'body' => 'This is closed',
            'status' => 'closed'
        ]);

        //WHEN
        $response = $this->getJson('/api/tickets?status=open');

        //THEN
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => [
                    [
                        'subject' => 'Open Ticket 1',
                        'status' => 'open'
                    ],
                    [
                        'subject' => 'Open Ticket 2',
                        'status' => 'open'
                    ]
                ]
            ]);
    }

    public function test_can_search_tickets_by_subject(): void
    {
        //GIVEN
        Ticket::factory()->create([
            'subject' => 'Bug in login system',
            'body' => 'User cannot login',
            'status' => 'open'
        ]);

        Ticket::factory()->create([
            'subject' => 'Feature request for dashboard',
            'body' => 'Need new dashboard features',
            'status' => 'new'
        ]);

        Ticket::factory()->create([
            'subject' => 'Login page design issue',
            'body' => 'Login page looks bad',
            'status' => 'pending'
        ]);

        //WHEN
        $response = $this->getJson('/api/tickets?search=login');

        //THEN
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => [
                    [
                        'subject' => 'Bug in login system'
                    ],
                    [
                        'subject' => 'Login page design issue'
                    ]
                ]
            ]);
    }

    public function test_can_search_tickets_by_body(): void
    {
        //GIVEN
        Ticket::factory()->create([
            'subject' => 'Issue 1',
            'body' => 'Database connection failed',
            'status' => 'open'
        ]);

        Ticket::factory()->create([
            'subject' => 'Issue 2',
            'body' => 'User interface is slow',
            'status' => 'new'
        ]);

        Ticket::factory()->create([
            'subject' => 'Issue 3',
            'body' => 'Database query optimization needed',
            'status' => 'pending'
        ]);

        //WHEN
        $response = $this->getJson('/api/tickets?search=database');

        //THEN
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => [
                    [
                        'subject' => 'Issue 1'
                    ],
                    [
                        'subject' => 'Issue 3'
                    ]
                ]
            ]);
    }



    public function test_can_sort_tickets_by_created_at_ascending(): void
    {
        //GIVEN
        $ticket1 = Ticket::factory()->create([
            'subject' => 'First Ticket',
            'body' => 'First ticket body',
            'status' => 'open',
            'created_at' => now()->subDays(3)
        ]);

        $ticket2 = Ticket::factory()->create([
            'subject' => 'Second Ticket',
            'body' => 'Second ticket body',
            'status' => 'new',
            'created_at' => now()->subDays(2)
        ]);

        $ticket3 = Ticket::factory()->create([
            'subject' => 'Third Ticket',
            'body' => 'Third ticket body',
            'status' => 'closed',
            'created_at' => now()->subDays(1)
        ]);

        //WHEN
        $response = $this->getJson('/api/tickets?sort_created_at=asc');

        //THEN
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [
                    [
                        'id' => $ticket1->id,
                        'subject' => 'First Ticket'
                    ],
                    [
                        'id' => $ticket2->id,
                        'subject' => 'Second Ticket'
                    ],
                    [
                        'id' => $ticket3->id,
                        'subject' => 'Third Ticket'
                    ]
                ]
            ]);
    }

    public function test_can_sort_tickets_by_created_at_descending(): void
    {
        //GIVEN
        $ticket1 = Ticket::factory()->create([
            'subject' => 'First Ticket',
            'body' => 'First ticket body',
            'status' => 'open',
            'created_at' => now()->subDays(3)
        ]);

        $ticket2 = Ticket::factory()->create([
            'subject' => 'Second Ticket',
            'body' => 'Second ticket body',
            'status' => 'new',
            'created_at' => now()->subDays(2)
        ]);

        $ticket3 = Ticket::factory()->create([
            'subject' => 'Third Ticket',
            'body' => 'Third ticket body',
            'status' => 'closed',
            'created_at' => now()->subDays(1)
        ]);

        //WHEN
        $response = $this->getJson('/api/tickets?sort_created_at=desc');

        //THEN
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJson([
                'data' => [
                    [
                        'id' => $ticket3->id,
                        'subject' => 'Third Ticket'
                    ],
                    [
                        'id' => $ticket2->id,
                        'subject' => 'Second Ticket'
                    ],
                    [
                        'id' => $ticket1->id,
                        'subject' => 'First Ticket'
                    ]
                ]
            ]);
    }
}
