<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientContact;

class ClientContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Client $client)
    {
        // クライアントとその担当者リレーションをロード
        $client->load('contacts');

        return view('clients.contacts.index', compact('client'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Client $client)
    {
        return view('clients.contacts.create', compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'note' => 'nullable|string',
        ]);

        $client->contacts()->create($validated);

        return redirect()->route('clients.show', $client->id)
            ->with('success', '担当者を追加しました');
    }


    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientContact $contact)
    {
        $client = $contact->client; // 所属クライアントを取得

        return view('clients.contacts.edit', compact('contact', 'client'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClientContact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'note' => 'nullable|string',
        ]);

        $contact->update($validated);

        return redirect()->route('clients.show', $contact->client_id)
            ->with('success', '担当者情報を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientContact $contact)
    {
        $clientId = $contact->client_id;

        $contact->delete();

        return redirect()->route('clients.show', $clientId)
            ->with('success', '担当者を削除しました');
    }
}
