<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function store(Request $request)
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

        return response()->json([
            'message' => 'Ticket criado com sucesso.',
            'data' => $ticket,
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
}
