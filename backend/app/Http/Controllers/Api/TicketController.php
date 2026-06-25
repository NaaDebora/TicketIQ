<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\TicketAiAnalysis;
use App\Services\GeminiService;

class TicketController extends Controller
{

    public function store(Request $request, GeminiService $geminiService)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $ticket = Ticket::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'aberto',
            'priority' => null,
            'category_id' => null,
        ]);

        $categories = Category::pluck('name')->toArray();

        $analysis = $geminiService->analyzeTicket(
            $ticket->title,
            $ticket->description,
            $categories
        );

        $category = Category::whereRaw('LOWER(name) = ?', [
            strtolower($analysis['category'] ?? '')
        ])->first();

        $ticket->update([
            'category_id' => $category?->id,
            'priority' => $analysis['priority'] ?? null,
            'status' => 'aguardando_retorno_usuario',
        ]);

        TicketAiAnalysis::updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'category_suggested' => $analysis['category'] ?? null,
                'priority_suggested' => $analysis['priority'] ?? null,
                'confidence' => $analysis['confidence'] ?? null,
                'possible_cause' => $analysis['possible_cause'] ?? null,
                'suggested_solution' => $analysis['suggested_solution'] ?? null,
                'raw_response' => $analysis,
            ]
        );

        return response()->json([
            'message' => 'Ticket criado e analisado com sucesso.',
            'data' => $ticket->load(['user', 'category', 'aiAnalysis']),
        ], 201);
    }

    public function index()
    {
        $tickets = Ticket::with([
            'user',
            'category',
            'aiAnalysis'
        ])->latest()->get();

        return response()->json([
            'data' => $tickets
        ]);
    }

    public function show($id)
    {
        $ticket = Ticket::with([
            'user',
            'category',
            'aiAnalysis'
        ])->findOrFail($id);

        return response()->json([
            'data' => $ticket
        ]);
    }

    public function analyze($id, GeminiService $geminiService)
    {
        $ticket = Ticket::findOrFail($id);

        $categories = Category::pluck('name')->toArray();

        $analysis = $geminiService->analyzeTicket(
            $ticket->title,
            $ticket->description,
            $categories
        );

        $category = Category::whereRaw('LOWER(name) = ?', [
            strtolower($analysis['category'] ?? 'Outro')
        ])->first();

        $ticket->update([
            'category_id' => $category?->id,
            'priority' => $analysis['priority'] ?? null,
        ]);

        TicketAiAnalysis::updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'category_suggested' => $analysis['category'] ?? null,
                'priority_suggested' => $analysis['priority'] ?? null,
                'confidence' => $analysis['confidence'] ?? null,
                'raw_response' => $analysis,
            ]
        );

        return response()->json([
            'message' => 'Ticket analisado com sucesso.',
            'data' => $ticket->load(['user', 'category', 'aiAnalysis']),
        ]);
    }

    public function feedback(Request $request, $id)
    {
        $validated = $request->validate([
            'resolved' => ['required', 'boolean'],
        ]);

        $ticket = Ticket::with(['user', 'category.department', 'aiAnalysis'])->findOrFail($id);
        if ($validated['resolved']) {
            $ticket->update([
                'status' => 'resolvido',
            ]);

            return response()->json([
                'message' => 'Chamado resolvido com sucesso.',
                'data' => $ticket->fresh()->load(['user', 'category.department', 'aiAnalysis']),
            ]);
        }

        $ticket->update([
            'status' => 'encaminhado',
        ]);

        return response()->json([
            'message' => 'Chamado encaminhado para o departamento responsável.',
            'department' => $ticket->category?->department,
            'data' => $ticket->fresh()->load(['user', 'category.department', 'aiAnalysis']),
        ]);
    }
}
