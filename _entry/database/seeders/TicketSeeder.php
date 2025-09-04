<?php

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
        $tickets = [
            // Bug reports
            [
                'subject' => 'Login button not working',
                'body' => 'Users cannot click the login button on the homepage. The button appears disabled.',
                'status' => 'open',
                'category' => 'bug',
                'note' => 'High priority - affects all users'
            ],
            [
                'subject' => 'Database connection timeout',
                'body' => 'Application crashes when trying to connect to database after 30 seconds.',
                'status' => 'pending',
                'category' => 'bug',
                'note' => 'Need to check database server logs'
            ],
            [
                'subject' => 'Email notifications not sending',
                'body' => 'Users are not receiving email notifications for new messages.',
                'status' => 'new',
                'category' => 'bug'
            ],
            [
                'subject' => 'Mobile app crashes on iOS',
                'body' => 'App crashes immediately after opening on iPhone 12 with iOS 15.',
                'status' => 'open',
                'category' => 'bug',
                'note' => 'Critical issue - affects mobile users'
            ],
            [
                'subject' => 'Search results are empty',
                'body' => 'Search functionality returns no results even when items exist.',
                'status' => 'pending',
                'category' => 'bug'
            ],

            // Feature requests
            [
                'subject' => 'Add dark mode theme',
                'body' => 'Please add a dark mode option for better user experience during night time.',
                'status' => 'new',
                'category' => 'feature',
                'note' => 'Popular user request'
            ],
            [
                'subject' => 'Export data to CSV',
                'body' => 'Users need ability to export their data to CSV format for backup purposes.',
                'status' => 'open',
                'category' => 'feature'
            ],
            [
                'subject' => 'Two-factor authentication',
                'body' => 'Implement 2FA for enhanced security of user accounts.',
                'status' => 'pending',
                'category' => 'feature',
                'note' => 'Security enhancement'
            ],
            [
                'subject' => 'Bulk operations for files',
                'body' => 'Allow users to select multiple files and perform bulk operations like delete or move.',
                'status' => 'new',
                'category' => 'feature'
            ],
            [
                'subject' => 'Advanced filtering options',
                'body' => 'Add more filtering options in the dashboard for better data organization.',
                'status' => 'open',
                'category' => 'feature'
            ],

            // Performance issues
            [
                'subject' => 'Slow page loading',
                'body' => 'Dashboard page takes more than 10 seconds to load with large datasets.',
                'status' => 'pending',
                'category' => 'performance',
                'note' => 'Affects user experience'
            ],
            [
                'subject' => 'Memory usage optimization',
                'body' => 'Application consumes too much memory when processing large files.',
                'status' => 'new',
                'category' => 'performance'
            ],
            [
                'subject' => 'Database query optimization',
                'body' => 'Some database queries are taking too long to execute.',
                'status' => 'open',
                'category' => 'performance'
            ],

            // UI/UX issues
            [
                'subject' => 'Button text is too small',
                'body' => 'Button text in the navigation menu is hard to read on mobile devices.',
                'status' => 'new',
                'category' => 'ui',
                'note' => 'Accessibility issue'
            ],
            [
                'subject' => 'Form validation messages unclear',
                'body' => 'Error messages in forms are not descriptive enough for users.',
                'status' => 'pending',
                'category' => 'ui'
            ],
            [
                'subject' => 'Color contrast issues',
                'body' => 'Text color does not have enough contrast with background color.',
                'status' => 'open',
                'category' => 'ui'
            ],

            // Integration issues
            [
                'subject' => 'API rate limiting too strict',
                'body' => 'API rate limiting is preventing legitimate users from accessing the service.',
                'status' => 'new',
                'category' => 'integration'
            ],
            [
                'subject' => 'Third-party service timeout',
                'body' => 'Integration with external payment service is timing out frequently.',
                'status' => 'pending',
                'category' => 'integration',
                'note' => 'Affects payment processing'
            ],
            [
                'subject' => 'Webhook delivery failures',
                'body' => 'Webhooks are not being delivered to external services consistently.',
                'status' => 'open',
                'category' => 'integration'
            ],

            // Security concerns
            [
                'subject' => 'Password policy too weak',
                'body' => 'Current password requirements are not strong enough for security.',
                'status' => 'new',
                'category' => 'security',
                'note' => 'Security audit finding'
            ],
            [
                'subject' => 'Session timeout too long',
                'body' => 'User sessions remain active for too long, creating security risk.',
                'status' => 'pending',
                'category' => 'security'
            ],
            [
                'subject' => 'File upload security',
                'body' => 'Need to add virus scanning for uploaded files.',
                'status' => 'open',
                'category' => 'security'
            ],

            // Documentation
            [
                'subject' => 'API documentation outdated',
                'body' => 'API documentation does not reflect current endpoints and parameters.',
                'status' => 'new',
                'category' => 'documentation'
            ],
            [
                'subject' => 'User guide missing features',
                'body' => 'User guide does not cover recently added features.',
                'status' => 'pending',
                'category' => 'documentation'
            ],
            [
                'subject' => 'Installation instructions unclear',
                'body' => 'Setup instructions for developers are confusing and incomplete.',
                'status' => 'open',
                'category' => 'documentation',
                'note' => 'Blocking new developers'
            ],

            // Miscellaneous
            [
                'subject' => 'Backup system not working',
                'body' => 'Automated backup system has not been running for the past week.',
                'status' => 'new',
                'category' => 'infrastructure',
                'note' => 'Critical - data loss risk'
            ],
            [
                'subject' => 'Monitoring alerts too noisy',
                'body' => 'System monitoring is sending too many false positive alerts.',
                'status' => 'pending',
                'category' => 'infrastructure'
            ],
            [
                'subject' => 'Log files growing too large',
                'body' => 'Application log files are consuming too much disk space.',
                'status' => 'open',
                'category' => 'infrastructure'
            ]
        ];

        foreach ($tickets as $ticketData) {
            Ticket::factory()->create($ticketData);
        }
    }
}
