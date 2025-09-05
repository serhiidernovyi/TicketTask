<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 30+ tickets using the factory with realistic data
        $this->createBugTickets();
        $this->createFeatureTickets();
        $this->createQuestionTickets();
        $this->createUnclassifiedTickets();
        $this->createManualTickets();
        $this->createClosedTickets();
        $this->createMixedTickets();
    }

    private function createBugTickets(): void
    {
        // Create 8 bug tickets
        Ticket::factory()
            ->count(8)
            ->bug()
            ->create();
    }

    private function createFeatureTickets(): void
    {
        // Create 6 feature request tickets
        Ticket::factory()
            ->count(6)
            ->feature()
            ->create();
    }

    private function createQuestionTickets(): void
    {
        // Create 5 question tickets
        Ticket::factory()
            ->count(5)
            ->question()
            ->create();
    }

    private function createUnclassifiedTickets(): void
    {
        // Create 4 unclassified tickets
        Ticket::factory()
            ->count(4)
            ->unclassified()
            ->create();
    }

    private function createManualTickets(): void
    {
        // Create 3 manually classified tickets
        Ticket::factory()
            ->count(3)
            ->manual()
            ->create();
    }

    private function createClosedTickets(): void
    {
        // Create 4 closed tickets
        Ticket::factory()
            ->count(4)
            ->closed()
            ->create();
    }

    private function createMixedTickets(): void
    {
        // Create 5 mixed tickets with random data
        Ticket::factory()
            ->count(5)
            ->create();
    }
}
