<?php

declare(strict_types=1);

namespace Classification\ValueObjects;

class ClassificationResult
{
    public function __construct(
        public readonly string $category,
        public readonly string $explanation,
        public readonly float $confidence
    ) {
        $this->validate();
    }

    /**
     * Validate the classification result
     */
    private function validate(): void
    {
        if ($this->confidence < 0.0 || $this->confidence > 1.0) {
            throw new \InvalidArgumentException('Confidence must be between 0.0 and 1.0');
        }

        $validCategories = ['bug', 'feature', 'question', 'complaint', 'compliment', 'general'];
        if (!in_array($this->category, $validCategories, true)) {
            throw new \InvalidArgumentException('Invalid category: ' . $this->category);
        }
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'category' => $this->category,
            'explanation' => $this->explanation,
            'confidence' => $this->confidence,
        ];
    }
}
