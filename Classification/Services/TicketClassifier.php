<?php

declare(strict_types=1);

namespace Classification\Services;

use App\Models\Ticket;
use Classification\Contracts\ClassifierInterface;
use Classification\ValueObjects\ClassificationResult;
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

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->getSystemPrompt()
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->buildUserPrompt($ticket)
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 500
            ]);

            return $this->parseResponse($response->choices[0]->message->content);

        } catch (\Exception $e) {
            \Log::error('OpenAI classification failed: ' . $e->getMessage());
            return $this->getRandomClassification();
        }
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
