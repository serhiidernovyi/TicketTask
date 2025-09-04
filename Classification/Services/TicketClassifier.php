<?php

declare(strict_types=1);

namespace Classification\Services;

use App\Models\Ticket;
use Classification\Contracts\ClassifierInterface;
use Classification\ValueObjects\ClassificationResult;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use OpenAI\Exceptions\ErrorException;
use OpenAI\Exceptions\TransporterException;
use OpenAI\Laravel\Facades\OpenAI;

class TicketClassifier implements ClassifierInterface
{
    private const CATEGORIES = [
        'bug', 'feature', 'question', 'complaint', 'compliment', 'general'
    ];

    /**
     * Classify a ticket using OpenAI API
     */
    public function classify(Ticket $ticket): ClassificationResult
    {
        if (!config('openai.classify_enabled', false)) {
            return $this->getRandomClassification();
        }

        return Cache::remember(
            "classify:ticket:{$ticket->id}:{$ticket->updated_at?->timestamp}",
            3600,
            fn () => $this->classifyWithLock($ticket)
        );
    }

    /**
     * Classify with lock to prevent concurrent requests
     */
    private function classifyWithLock(Ticket $ticket): ClassificationResult
    {
        return Cache::lock("classify:ticket:{$ticket->id}", 15)->block(5, function () use ($ticket) {
            return $this->classifyOnce($ticket);
        });
    }

    /**
     * Single classification attempt
     */
    private function classifyOnce(Ticket $ticket): ClassificationResult
    {
        try {
            $response = $this->callOpenAI([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $this->getSystemPrompt()],
                    ['role' => 'user',   'content' => $this->buildUserPrompt($ticket)],
                ],
                'temperature' => 0.2,
                'max_tokens'  => 80,
            ]);

            return $this->parseResponse($response->choices[0]->message->content);
        } catch (\Throwable $e) {
            \Log::warning('OpenAI classification fallback', [
                'err' => $e->getMessage(),
                'ticket_id' => $ticket->id,
            ]);
            return $this->getRandomClassification();
        }
    }

    /**
     * Call OpenAI with retry logic
     */
    private function callOpenAI(array $payload)
    {
        $attempt = 0;

        return retry(
            5,
            function () use (&$attempt, $payload) {
                $attempt++;
                
                return OpenAI::chat()->create($payload);
            },
            function ($attempts) {
                return 400 * (2 ** ($attempts - 1));
            },

            function ($e) {
                if ($e instanceof ErrorException || $e instanceof TransporterException) {
                    $msg = Str::lower($e->getMessage());
                    return str_contains($msg, 'rate limit') || str_contains($msg, '429') ||
                           str_contains($msg, 'timeout')    || str_contains($msg, 'temporarily');
                }
                return false;
            }
        );
    }

    /**
     * Get system prompt for OpenAI
     */
    private function getSystemPrompt(): string
    {
        return "You are a ticket classification system. Analyze the ticket content and classify it into one of these categories: " . implode(', ', self::CATEGORIES) . ".

Return ONLY a valid JSON response with these exact keys:
{
  \"category\": \"one of the categories above\",
  \"explanation\": \"brief explanation why this category was chosen\",
  \"confidence\": 0.85
}

Rules:
- bug: errors, crashes, broken functionality
- feature: requests for new functionality, enhancements
- question: help requests, how-to questions
- complaint: negative feedback, frustration
- compliment: positive feedback, praise
- general: anything that doesn't fit other categories
- confidence: decimal between 0.0 and 1.0";
    }

    /**
     * Build user prompt with ticket content
     */
    private function buildUserPrompt(Ticket $ticket): string
    {
        return "Subject: {$ticket->subject}\n\nBody: {$ticket->body}";
    }

    /**
     * Parse OpenAI response
     */
    private function parseResponse(string $content): ClassificationResult
    {
        try {
            $decoded = json_decode(trim($content), true, 512, JSON_THROW_ON_ERROR);
            
            return new ClassificationResult(
                category: $decoded['category'] ?? 'general',
                explanation: $decoded['explanation'] ?? 'No explanation provided',
                confidence: (float) ($decoded['confidence'] ?? 0.5)
            );
        } catch (\JsonException $e) {
            \Log::error('Failed to parse OpenAI response: ' . $e->getMessage());
            return $this->getRandomClassification();
        }
    }

    /**
     * Get random classification when OpenAI is disabled
     */
    private function getRandomClassification(): ClassificationResult
    {
        $category = self::CATEGORIES[array_rand(self::CATEGORIES)];
        
        return new ClassificationResult(
            category: $category,
            explanation: "Random classification (OpenAI disabled): {$category}",
            confidence: round(0.3 + (mt_rand() / mt_getrandmax()) * 0.4, 2) // 0.3-0.7
        );
    }
}
