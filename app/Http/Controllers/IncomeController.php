<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncomeController extends Controller
{
    public function index()
    {
       
        $configuration = Configuration::orderBy('created_at', 'desc')->first();
        if (is_null($configuration) || is_null($configuration->promotion)) {
            return redirect()->back()->with(['error' => 'Veuillez remplir la promotion dans la configuration.'], 422);
        }
        $incomes = Income::orderBy('created_at', 'desc')->where('promotion', $configuration->promotion)->get();
        return Inertia::render('Incomes/Incomes', ['incomes' => $incomes]);

       
    }


    public function store(Request $request)
    {
        $configuration = Configuration::orderBy('created_at', 'desc')->first();

        if (!$configuration) {
            return response()->json(['error' => 'Configuration not found'], 404);
        }
        $validatedData = $request->validate([
            'source' => ['required'],
            'description' => [''],
            'amount' => ['required'],
            'income_date' => ['required'],
        ]);
       // $validatedData['id_users'] = $utilisateur;
        $validatedData['promotion'] = $configuration->promotion;
        $income = Income::create($validatedData);
    }
    public function update(Request $request, Income $income)
    {
        $income->source = $request->source;
        $income->amount = $request->amount;
        $income->description = $request->description;
        $income->income_date = $request->income_date;
        $income->save();
    }
}
