<?php

namespace App\Http\Controllers;

use App\Models\TransactionActivity;
use App\Models\User;
use App\Models\Ebook;
use Illuminate\Http\Request;

class TransactionActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('admin.reports.transactions');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $ebooks = Ebook::all();
        return view('transaction-activities.create', compact('users', 'ebooks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ebook_id' => 'required|exists:ebooks,id',
            'activity_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
        ]);

        TransactionActivity::create($request->all());

        return redirect()->route('admin.transaction-activities.index')->with('success', 'Transaction Activity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionActivity $transactionActivity)
    {
        $activityNumber = TransactionActivity::where('id', '<=', $transactionActivity->id)->count();

        return view('transaction-activities.show', compact('transactionActivity', 'activityNumber'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionActivity $transactionActivity)
    {
        $users = User::all();
        $ebooks = Ebook::all();
        return view('transaction-activities.edit', compact('transactionActivity', 'users', 'ebooks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionActivity $transactionActivity)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ebook_id' => 'required|exists:ebooks,id',
            'activity_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $transactionActivity->update($request->all());

        return redirect()->route('admin.transaction-activities.index')->with('success', 'Transaction Activity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionActivity $transactionActivity)
    {
        $transactionActivity->delete();

        return redirect()->route('admin.transaction-activities.index')->with('success', 'Transaction Activity deleted successfully.');
    }
}
