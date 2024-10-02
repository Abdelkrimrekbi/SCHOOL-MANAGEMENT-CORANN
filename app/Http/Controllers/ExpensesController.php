<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Expenses;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpensesController extends Controller
{
    public function index()
    {
        $configuration = Configuration::orderBy('created_at', 'desc')->first();
        if (is_null($configuration) || is_null($configuration->promotion)) {
            return redirect()->back()->with(['error' => 'Veuillez remplir la promotion dans la configuration.'], 422);
        }
        $expenses = Expenses::orderBy('created_at', 'desc')->where('promotion', $configuration->promotion)->get();
       // dd($expenses);
        return Inertia::render('Expenses/Expenses', ['expenses' => $expenses]);

    }
    
    public function store(Request $request)
    {
          
            $configuration = configuration::orderBy('created_at', 'desc')->first();
            
            if (!$configuration) {
                return response()->json(['error' => 'Configuration not found'], 404);
            }
            $validatedData = $request->validate([
                'title' => ['required'],
                'description' => [''],
                'amount' => ['required'],
                'expense_date' => ['required'],
            ]);
           
           // $validatedData['id_users'] = $utilisateur;
            $validatedData['promotion'] = $configuration->promotion;
          
            $expense = Expenses::create($validatedData);
        }
    
    public function update(Request $request, Expenses $expense)
    {
        $expense->title = $request->title;
        $expense->amount = $request->amount;
        $expense->description = $request->description;
        $expense->expense_date = $request->expense_date;
        $expense->save();
    }
    public function destroy(Expenses $expense)
    {
        $expense->delete();
    }
}
