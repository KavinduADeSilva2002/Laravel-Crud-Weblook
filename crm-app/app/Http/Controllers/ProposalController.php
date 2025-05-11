<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProposalController extends Controller
{
    public function index()
    {
        return Inertia::render('Proposals/Index', [
            'customers' => Customer::all(),
            'proposals' => Proposal::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        Proposal::create($validated);

        return redirect()->route('proposals.index')->with('success', 'Proposal created.');
    }

    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $proposal->update($validated);

        return redirect()->route('proposals.index')->with('success', 'Proposal updated.');
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();

        return redirect()->route('proposals.index')->with('success', 'Proposal deleted.');
    }
}
