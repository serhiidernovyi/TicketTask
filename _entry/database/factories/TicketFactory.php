<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['bug', 'feature', 'question', 'complaint', 'compliment', 'general'];
        $statuses = ['new', 'open', 'pending', 'closed'];

        $subjects = [
            'Login issues with two-factor authentication',
            'Application crashes when uploading large files',
            'Request for dark mode theme',
            'Database connection timeout errors',
            'User interface not responsive on mobile',
            'Email notifications not being sent',
            'Search functionality returning incorrect results',
            'File upload progress bar not showing',
            'Password reset email not received',
            'Dashboard charts not displaying data',
            'API rate limiting too restrictive',
            'User profile picture not updating',
            'Export feature missing some data',
            'Print functionality not working',
            'Calendar events not syncing',
            'Payment processing errors',
            'Slow page load times',
            'Missing validation error messages',
            'Incorrect timezone display',
            'Unable to delete user accounts',
            'Report generation failing',
            'Notification preferences not saving',
            'Data export in wrong format',
            'User permissions not working correctly',
            'System maintenance notification needed'
        ];

        $bodies = [
            'I am experiencing issues with the login process. When I try to log in with my credentials, the system shows an error message but doesn\'t specify what\'s wrong. This started happening after the last update.',
            'The application keeps crashing whenever I try to upload files larger than 10MB. I get a white screen and have to refresh the page. This is affecting my productivity.',
            'I would like to request a dark mode theme for the application. The current bright interface is causing eye strain during long work sessions, especially in low-light environments.',
            'We are getting frequent database connection timeout errors in production. This is affecting our users and causing data loss in some cases. The issue seems to be intermittent.',
            'The user interface is not responsive on mobile devices. Buttons are too small to tap and the layout breaks on smaller screens. This makes the app unusable on phones.',
            'Email notifications are not being sent to users when they should receive them. This is causing confusion and missed important updates. Please investigate this issue.',
            'The search functionality is returning incorrect results. When I search for "user management", it shows results for "file management" instead. This is very frustrating.',
            'The file upload progress bar is not showing during uploads. Users don\'t know if their upload is progressing or if it has stalled. This needs to be fixed.',
            'I requested a password reset but never received the email. I checked my spam folder and it\'s not there either. This is preventing me from accessing my account.',
            'The dashboard charts are not displaying any data even though there should be data available. The charts show empty states instead of the actual metrics.',
            'The API rate limiting is too restrictive. We are hitting the limits too quickly during normal usage. Please increase the rate limits or provide a way to request higher limits.',
            'User profile pictures are not updating when users upload new ones. The old picture continues to show even after successful upload. This is confusing for users.',
            'The export feature is missing some data that should be included. When I export the report, several columns are empty that should contain information.',
            'The print functionality is not working properly. When I try to print a page, it either shows a blank page or cuts off content. This is affecting our workflow.',
            'Calendar events are not syncing properly with external calendars. Events created in the app don\'t appear in Google Calendar and vice versa.',
            'We are experiencing payment processing errors. Some payments are being charged but not recorded in the system, while others are being declined incorrectly.',
            'Page load times have become very slow recently. It takes 10-15 seconds to load simple pages that used to load in 2-3 seconds. This is affecting user experience.',
            'Validation error messages are not showing when users enter invalid data. Users don\'t know what they did wrong or how to fix it. This needs to be addressed.',
            'The timezone is displaying incorrectly. All times are showing in UTC instead of the user\'s local timezone. This is causing confusion about when events occurred.',
            'I am unable to delete user accounts from the admin panel. The delete button is there but nothing happens when I click it. This is a critical issue.',
            'Report generation is failing with an error message. I can\'t generate any reports, which is blocking our monthly reporting process. Please fix this urgently.',
            'Notification preferences are not being saved. Users set their preferences but they revert back to defaults after logging out and back in.',
            'Data export is generating files in the wrong format. I requested CSV but got JSON instead. This is causing issues with our data processing workflow.',
            'User permissions are not working correctly. Some users can access features they shouldn\'t have access to, while others can\'t access features they should have.',
            'We need a system maintenance notification feature. When we perform maintenance, users should be notified in advance so they can plan accordingly.'
        ];

        $subject = $this->faker->randomElement($subjects);
        $body = $this->faker->randomElement($bodies);
        $category = $this->faker->randomElement($categories);
        $status = $this->faker->randomElement($statuses);

        $hasAiData = $this->faker->boolean(70); // 70% chance of having AI data
        $isManual = $this->faker->boolean(20); // 20% chance of being manually set

        return [
            'subject' => $subject,
            'body' => $body,
            'status' => $status,
            'category' => $hasAiData ? $category : null,
            'explanation' => $hasAiData ? $this->faker->sentence(15) : null,
            'confidence' => $hasAiData ? $this->faker->randomFloat(2, 0.6, 0.95) : null,
            'note' => $this->faker->boolean(30) ? $this->faker->sentence(10) : null,
            'category_is_manual' => $isManual,
            'category_changed_at' => $isManual ? $this->faker->dateTimeBetween('-30 days', 'now') : null,
        ];
    }

    /**
     * Create a bug ticket
     */
    public function bug(): static
    {
        return $this->state(fn (array $attributes) => [
            'subject' => 'Bug: ' . $this->faker->sentence(4),
            'body' => 'I found a bug in the application. ' . $this->faker->paragraph(3),
            'category' => 'bug',
            'status' => 'open',
            'confidence' => $this->faker->randomFloat(2, 0.8, 0.95),
            'explanation' => 'This appears to be a software defect based on the error description and user behavior.',
        ]);
    }

    /**
     * Create a feature request ticket
     */
    public function feature(): static
    {
        return $this->state(fn (array $attributes) => [
            'subject' => 'Feature Request: ' . $this->faker->sentence(4),
            'body' => 'I would like to request a new feature. ' . $this->faker->paragraph(3),
            'category' => 'feature',
            'status' => 'new',
            'confidence' => $this->faker->randomFloat(2, 0.7, 0.9),
            'explanation' => 'This is a request for new functionality or enhancement to existing features.',
        ]);
    }

    /**
     * Create a question ticket
     */
    public function question(): static
    {
        return $this->state(fn (array $attributes) => [
            'subject' => 'Question: ' . $this->faker->sentence(4),
            'body' => 'I have a question about how to use the application. ' . $this->faker->paragraph(2),
            'category' => 'question',
            'status' => 'open',
            'confidence' => $this->faker->randomFloat(2, 0.6, 0.8),
            'explanation' => 'This appears to be a user inquiry seeking information or clarification.',
        ]);
    }

    /**
     * Create an unclassified ticket
     */
    public function unclassified(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => null,
            'explanation' => null,
            'confidence' => null,
            'category_is_manual' => false,
            'category_changed_at' => null,
        ]);
    }

    /**
     * Create a manually classified ticket
     */
    public function manual(): static
    {
        return $this->state(fn (array $attributes) => [
            'category_is_manual' => true,
            'category_changed_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ]);
    }

    /**
     * Create a closed ticket
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
            'note' => 'Resolved: ' . $this->faker->sentence(8),
        ]);
    }
}
