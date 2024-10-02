<?php

namespace App\Http\Controllers;

use App\Models\group;
use App\Models\Level;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Configuration;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::orderBy('created_at', 'desc')->get();
        $students = Student::orderBy('created_at', 'desc')->get();
        $levels = Level::orderBy('created_at', 'desc')->get();
        $classes = group::orderBy('created_at', 'desc')->get();
        return Inertia::render('Payments/Payments', ['payments' => $payments, 'students' => $students, 'levels' => $levels, 'classes' => $classes]);
    }

    public function generatePDF(Request $request, $id, $ids)
    {
        $configirations = Configuration::orderBy('created_at', 'desc')->get();
        $items = Student::where('id', $ids)->get();
        $exams = Payment::where('id', $id)->get();
        $groups = group::whereIn("id", Student::where('id', $ids)->pluck("class_id"))->get();

        $path = "storage/" . $configirations->first()->logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdf = Pdf::loadView('pdf_template', [
            'exams' => $exams, 'items' => $items, 'configirations' => $configirations,
            'image' => $base64, "groups" => $groups
        ]);

        return $pdf->download('document.pdf');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $configuration = Configuration::orderBy('created_at', 'desc')->first();
        if ($request->type === "Fees") {
            $payments = [];
            $amount = intval($request->amount);
            $totalMonths = count($request->month);
            foreach ($request->month as $index => $month) {
                $tempAmount  = $amount - $request->prix_level;
                $data = [];
                $monthFound = Payment::where(['month' => $month, 'student_id' => $request->student_id])->first();

                if ($monthFound) {
                    if ($tempAmount >= 0) {
                        $amount -= ($request->prix_level - $monthFound->amount);
                        $data1 = [
                            'amount' => $monthFound->amount + ($request->prix_level - $monthFound->amount),
                            'rest' => 0,
                        ];
                    } else {
                        $data1 = [
                            'amount' => $monthFound->amount + $amount,
                            'rest' => abs($monthFound->rest - $amount),
                        ];
                    }
                    $monthFound->update($data1);
                } else {
                    if ($tempAmount  >= 0) {
                        if ($index === $totalMonths - 1) {
                            $data = [
                                'date_payment' => $request->date_payment,
                                'type' => $request->type,
                                'amount' => $amount,
                                'month' => $month,
                                'rest' => $request->rest,
                                'student_id' => $request->student_id,
                                'promotion' => $configuration->promotion,
                                'comments' => $request->comments,
                            ];
                        } else {
                            $amount -= $request->prix_level;
                            $data = [
                                'date_payment' => $request->date_payment,
                                'type' => $request->type,
                                'amount' => $request->prix_level,
                                'month' => $month,
                                'rest' => 0,
                                'student_id' => $request->student_id,
                                'promotion' => $configuration->promotion,
                                'comments' => $request->comments,
                            ];
                        }
                    } else {
                        $data = [
                            'date_payment' => $request->date_payment,
                            'type' => $request->type,
                            'amount' => $amount,
                            'month' => $month,
                            'rest' => $request->rest,
                            'student_id' => $request->student_id,
                            'promotion' => $configuration->promotion,
                            'comments' => $request->comments,
                        ];
                    }
                    $payments[] = Payment::create($data);
                }
            }
        } else {
          //  Log::debug($configuration->promotion);
           // Log::debug($request);
            $validatedData = $request->validate([
                'date_payment' => 'required|date',
                'type' => 'required|string',
                'amount' => 'required|numeric',
                'rest' => 'required|numeric',
                'student_id' => 'required|integer',
                'comments' => 'nullable|string',
            ]);
            $validatedData['month'] = $configuration->promotion;
            $validatedData['promotion'] = $configuration->promotion;
           // Log::debug($validatedData);
            Payment::create($validatedData);
        }
    }

    public function studentNoPaye() {
        $currentMonth = Carbon::now()->month;
        $studentsWithAllPayments = Payment::select('student_id')
                                          ->where('month', '<', $currentMonth)
                                          ->groupBy('student_id')
                                          ->havingRaw('COUNT(*) = ?', [$currentMonth - 1])
                                          ->pluck('student_id');

        $studentsWhoDidNotPay = Student::whereNotIn('id', $studentsWithAllPayments)->get();

        return response()->json($studentsWhoDidNotPay);
    }

    /**
     * Display the specified resource.
     */
    public function show(payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(payment $payment)
    {
        //
    }
}
