<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StatsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_returns_empty_stats_when_no_tickets(): void
    {
        // GIVEN
        // No tickets in database

        // WHEN
        $response = $this->getJson('/api/stats');

        // THEN
        $response->assertStatus(200)
            ->assertJson([
                'categories' => [],
                'statuses' => [],
                'daily_stats' => [],
                'overview' => [
                    'total_tickets' => 0,
                    'unclassified' => 0,
                    'manual_classifications' => 0,
                    'ai_classifications' => 0,
                    'avg_confidence' => 0,
                ],
                'top_issues' => [],
            ]);
    }

    public function test_returns_category_stats(): void
    {
        // GIVEN
        Ticket::factory()->create([
            'subject' => 'Bug report 1',
            'body' => 'Test bug',
            'category' => 'bug',
            'category_is_manual' => false
        ]);
        Ticket::factory()->create([
            'subject' => 'Bug report 2',
            'body' => 'Test bug',
            'category' => 'bug',
            'category_is_manual' => false
        ]);
        Ticket::factory()->create([
            'subject' => 'Feature request',
            'body' => 'Test feature',
            'category' => 'feature',
            'category_is_manual' => true
        ]);
        Ticket::factory()->create([
            'subject' => 'Question',
            'body' => 'Test question',
            'category' => 'question',
            'category_is_manual' => false
        ]);

        // WHEN
        $response = $this->getJson('/api/stats');

        // THEN
        $response->assertStatus(200)
            ->assertJsonStructure([
                'categories' => [
                    'bug',
                    'feature',
                    'question',
                ],
            ]);

        $categories = $response->json('categories');
        $this->assertEquals(2, $categories['bug']);
        $this->assertEquals(1, $categories['feature']);
        $this->assertEquals(1, $categories['question']);
    }

    public function test_returns_status_stats(): void
    {
        // GIVEN
        Ticket::factory()->create(['subject' => 'Open ticket 1', 'body' => 'Test', 'status' => 'open']);
        Ticket::factory()->create(['subject' => 'Open ticket 2', 'body' => 'Test', 'status' => 'open']);
        Ticket::factory()->create(['subject' => 'Pending ticket', 'body' => 'Test', 'status' => 'pending']);
        Ticket::factory()->create(['subject' => 'Closed ticket', 'body' => 'Test', 'status' => 'closed']);

        // WHEN
        $response = $this->getJson('/api/stats');

        // THEN
        $response->assertStatus(200)
            ->assertJsonStructure([
                'statuses' => [
                    'open',
                    'pending',
                    'closed',
                ],
            ]);

        $statuses = $response->json('statuses');
        $this->assertEquals(2, $statuses['open']);
        $this->assertEquals(1, $statuses['pending']);
        $this->assertEquals(1, $statuses['closed']);
    }

    public function test_returns_overview_stats(): void
    {
        // GIVEN
        Ticket::factory()->create([
            'subject' => 'Bug ticket',
            'body' => 'Test bug',
            'category' => 'bug',
            'category_is_manual' => false,
            'confidence' => 0.9
        ]);
        Ticket::factory()->create([
            'subject' => 'Feature ticket',
            'body' => 'Test feature',
            'category' => 'feature',
            'category_is_manual' => true,
            'confidence' => 0.8
        ]);
        Ticket::factory()->create([
            'subject' => 'Unclassified ticket',
            'body' => 'Test unclassified',
            'category' => null
        ]);

        // WHEN
        $response = $this->getJson('/api/stats');

        // THEN
        $response->assertStatus(200)
            ->assertJson([
                'overview' => [
                    'total_tickets' => 3,
                    'unclassified' => 1,
                    'manual_classifications' => 1,
                    'ai_classifications' => 1,
                    'avg_confidence' => 0.85,
                ],
            ]);
    }

    public function test_returns_daily_stats(): void
    {
        // GIVEN
        Ticket::factory()->create([
            'subject' => 'Yesterday ticket',
            'body' => 'Test',
            'created_at' => now()->subDays(1)
        ]);
        Ticket::factory()->create([
            'subject' => 'Two days ago ticket',
            'body' => 'Test',
            'created_at' => now()->subDays(2)
        ]);
        Ticket::factory()->create([
            'subject' => 'Closed yesterday',
            'body' => 'Test',
            'status' => 'closed',
            'updated_at' => now()->subDays(1)
        ]);

        // WHEN
        $response = $this->getJson('/api/stats');

        // THEN
        $response->assertStatus(200)
            ->assertJsonStructure([
                'daily_stats' => [
                    '*' => [
                        'date',
                        'created',
                        'closed',
                    ],
                ],
            ]);

        $dailyStats = $response->json('daily_stats');
        $this->assertCount(30, $dailyStats);

        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        $todayData = collect($dailyStats)->firstWhere('date', $today);
        $yesterdayData = collect($dailyStats)->firstWhere('date', $yesterday);

        $this->assertNotNull($todayData);
        $this->assertNotNull($yesterdayData);
    }

    public function test_returns_top_issues(): void
    {
        // GIVEN
        Ticket::factory()->count(5)->create([
            'subject' => 'Bug ticket',
            'body' => 'Test bug',
            'category' => 'bug'
        ]);
        Ticket::factory()->count(3)->create([
            'subject' => 'Feature ticket',
            'body' => 'Test feature',
            'category' => 'feature'
        ]);
        Ticket::factory()->count(2)->create([
            'subject' => 'Question ticket',
            'body' => 'Test question',
            'category' => 'question'
        ]);

        // WHEN
        $response = $this->getJson('/api/stats');

        // THEN
        $response->assertStatus(200)
            ->assertJsonStructure([
                'top_issues' => [
                    '*' => [
                        'category',
                        'count',
                        'percentage',
                    ],
                ],
            ]);

        $topIssues = $response->json('top_issues');
        $this->assertCount(3, $topIssues);

        $this->assertEquals('bug', $topIssues[0]['category']);
        $this->assertEquals(5, $topIssues[0]['count']);
        $this->assertEquals(50.0, $topIssues[0]['percentage']);
    }

    public function test_respects_rate_limiting(): void
    {
        // GIVEN
        // Rate limit is 30 requests per minute

        // WHEN
        for ($i = 0; $i < 31; $i++) {
            $response = $this->getJson('/api/stats');

            // THEN
            if ($i < 30) {
                $response->assertStatus(200);
            } else {
                $response->assertStatus(429);
            }
        }
    }

    public function test_returns_consistent_json_structure(): void
    {
        // GIVEN
        Ticket::factory()->create([
            'subject' => 'Test ticket',
            'body' => 'Test body'
        ]);

        // WHEN
        $response = $this->getJson('/api/stats');

        // THEN
        $response->assertStatus(200)
            ->assertJsonStructure([
                'categories',
                'statuses',
                'daily_stats',
                'overview' => [
                    'total_tickets',
                    'unclassified',
                    'manual_classifications',
                    'ai_classifications',
                    'avg_confidence',
                ],
                'top_issues',
            ]);
    }
}
