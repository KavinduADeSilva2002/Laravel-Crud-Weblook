<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index()
    {
        return Proposal::with('customer')->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        return Proposal::create($validated)->load('customer');
    }

    public function show(Proposal $proposal)
    {
        return $proposal->load('customer');
    }

    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'status' => 'in:pending,approved,rejected',
        ]);

        $proposal->update($validated);
        return $proposal->load('customer');
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return response()->noContent();
    }
}