<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;
use RuntimeException;

class AIQueryServiceSherlock
{
    private string $apiKey;
    private string $apiEndpoint = 'https://api.openai.com/v1/chat/completions';
    private array $conversationHistory = [];
    private const MAX_RETRIES = 3;
    private const RETRY_DELAY = 100;
    private const TIMEOUT = 60; // Extended timeout for complex analyses

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        if (empty($this->apiKey)) {
            Log::error('OpenAI API key missing');
            throw new RuntimeException('OpenAI API key is not configured');
        }
    }

    /**
     * Initialize a new conversation with a system prompt
     */
    public function initConversation(string $systemPrompt = null): void
    {
        $this->conversationHistory = [];
        
        if ($systemPrompt) {
            $this->conversationHistory[] = [
                'role' => 'system',
                'content' => $systemPrompt
            ];
        } else {
            $this->conversationHistory[] = [
                'role' => 'system',
                'content' => $this->buildDefaultSystemPrompt()
            ];
        }
        
        Log::info('AI conversation initialized', [
            'system_prompt' => $systemPrompt ?? $this->buildDefaultSystemPrompt()
        ]);
    }

    /**
     * Add a message to the conversation
     */
    public function addMessage(string $content, string $role = 'user'): void
    {
        $this->conversationHistory[] = [
            'role' => $role,
            'content' => $content
        ];
        
        // Log only the first 200 characters to avoid cluttering logs
        $logContent = strlen($content) > 200 ? substr($content, 0, 200) . '...' : $content;
        Log::info("Added {$role} message to AI conversation", [
            'content_preview' => $logContent,
            'content_length' => strlen($content)
        ]);
    }

    /**
     * Add structured data to the conversation
     */
    public function addStructuredData(string $description, array $data): void
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        $content = "{$description}:\n```json\n{$jsonData}\n```";
        
        $this->addMessage($content);
    }

    /**
     * Get a response from the AI
     */
    public function getResponse(): string
    {
        try {
            Log::info('Sending request to OpenAI API', [
                'message_count' => count($this->conversationHistory),
                'model' => 'gpt-4o-mini'
            ]);
            
            $response = $this->makeOpenAIRequest();
            $result = $response['choices'][0]['message']['content'] ?? '';
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error getting AI response', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return "Erreur lors de l'analyse AI: " . $e->getMessage();
        }
    }

    /**
     * Make the actual request to OpenAI API
     */
    private function makeOpenAIRequest(): array
    {
        try {
            $response = Http::timeout(self::TIMEOUT)
                ->retry(self::MAX_RETRIES, self::RETRY_DELAY)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey
                ])
                ->post($this->apiEndpoint, [
                    'model' => 'gpt-4o-mini',
                    'messages' => $this->conversationHistory,
                    'temperature' => 0.3,
                ]);

            if ($response->failed()) {
                throw new RequestException($response);
            }

            return $response->json();
        } catch (RequestException $e) {
            Log::error('OpenAI API request failed', [
                'status' => $e->getCode(),
                'response' => $e->response?->body(),
                'headers' => $e->response?->headers()
            ]);
            throw new RuntimeException('OpenAI API error: ' . $e->getMessage());
        }
    }

    /**
     * Build the default system prompt
     */
    private function buildDefaultSystemPrompt(): string
    {
        return "Tu es Sherlock Conseiller, un expert en analyse et recommandation pour les boulangeries-pâtisseries. " .
               "Tu analyses les données commerciales et financières pour fournir des insights stratégiques. " .
               "Tu as accès à de nombreux jeux de données sur la production, les ventes, les employés, et les finances. " .
               "Ton objectif est de détecter les problèmes, d'identifier les opportunités, et de fournir des recommandations concrètes et actionnables. " .
               "Tu dois rendre tes analyses accessibles et utiles pour le directeur général d'une boulangerie-pâtisserie. " .
               "Analyse méthodiquement les données et organise tes conclusions par sections distinctes.";
    }
}