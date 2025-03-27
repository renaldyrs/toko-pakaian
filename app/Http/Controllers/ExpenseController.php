<?php

// app/Http/Controllers/ExpenseController.php
namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->paginate(10);
        $totalExpenses = Expense::sum('amount');
        $categories = Expense::select('category')->distinct()->pluck('category');
        
        return view('expense.index', compact('expenses', 'totalExpenses', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string'
        ]);

        Expense::create([
            'date' => $request->date,
            'amount' => $request->amount,
            'category' => $request->category,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Pengeluaran berhasil dicatat');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus');
    }
}