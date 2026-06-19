<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function analyzeTicket(string $title, string $description, array $categories): array
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model');

        $categoriesList = implode(', ', $categories);

        $prompt = "
Você é um analista de suporte técnico.

Analise o chamado abaixo e classifique com base nas categorias permitidas.

Categorias permitidas:
{$categoriesList}

Prioridades permitidas:
Baixa, Média, Alta, Crítica

Título:
{$title}

Descrição:
{$description}

Retorne APENAS um JSON válido no seguinte formato:
{
  \"category\": \"\",
  \"priority\": \"\",
  \"confidence\": 0,
  \"possible_cause\": \"\",
  \"suggested_solution\": \"\"
}
";

        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]
        );

        if (!$response->successful()) {
            throw new \Exception('Erro ao consultar Gemini: ' . $response->body());
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        $text = trim($text);
        $text = str_replace(['```json', '```'], '', $text);

        return json_decode($text, true);
    }
}
