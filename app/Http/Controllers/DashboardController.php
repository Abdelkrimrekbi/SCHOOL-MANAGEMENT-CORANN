<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Payment; 

use Illuminate\Support\Facades\DB;
use App\Models\Configuration;
use App\Models\Expenses;
use App\Models\Income;
use Carbon\Carbon;
class DashboardController extends Controller
{
   
    // public function index()
    // {
    //     //$teachers=Teachers::orderBy('created_at', 'desc')->get();
    //     $teachersCount = DB::table('teachers')->whereNull('deleted_at')->count();
    //     $studentsCount = DB::table('students')->count();
    //     $parentCount = DB::table('guardians')->count();
    //    // $pyments = Payment::orderBy('created_at', 'desc')->get();

            
    //           // $pyments = DB::table('payments')
    //             //->where('promotion', Configuration::first()->promotion)
                
    //             $pyments = DB::table('payments')
    // ->select(DB::raw('MONTHNAME(created_at) as month'), DB::raw('SUM(amount) as total'))
    // ->groupBy(DB::raw('MONTHNAME(created_at)'))
    // ->orderByRaw("FIELD(MONTHNAME(created_at), 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
    // ->get();
    // $expenses = DB::table('expenses')
    // ->select(DB::raw('MONTHNAME(created_at) as month'), DB::raw('SUM(amount) as total'))
    // ->groupBy(DB::raw('MONTHNAME(created_at)'))
    // ->orderByRaw("FIELD(MONTHNAME(created_at), 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
    // ->get();


    //                    $currentMonth = Carbon::now()->month;
    //                    $currentYear = Carbon::now()->year;
                   
    //                    $paymentsByMonth = Payment::whereMonth('created_at', $currentMonth)
    //                        ->whereYear('created_at', $currentYear)
    //                        ->sum('amount' );
    //                        $ByMonthINcome = Income::whereMonth('created_at', $currentMonth)
    //                        ->whereYear('created_at', $currentYear)
    //                        ->sum('amount' );
    //                        $ByMonthExpense = Expenses::whereMonth('created_at', $currentMonth)
    //                        ->whereYear('created_at', $currentYear)
    //                        ->sum('amount' );

                
    //                  // dd($paymentsByMonth);
                    


                      
    //     return Inertia::render('Dashboard', ['teachersCount' => $teachersCount,'studentsCount'=>$studentsCount,'parentCount'=>$parentCount,
    //     'pyments'=>$pyments,'paymentsByMonth'=> $paymentsByMonth,'ByMonthExpense'=>$ByMonthExpense ,'ByMonthINcome'=>$ByMonthINcome,'expenses'=>$expenses]);
    // }
    public function index()
    {
        $configuration = Configuration::orderBy('created_at', 'desc')->first();
        // dd($configuration);

        if (is_null($configuration)) {
            return Inertia::render('Dashboard', [
                'teachersCount' => collect([]),
                'studentsCount' => collect([]),
                'pyments' => collect([]),
                'totalIncomeAndPaymentAmount' => collect([]),
                'error' => 'No configuration found',
            ]);
        }
       
        // $teachersCount = DB::table('teachers')->where('promotion', $configuration->promotion)->count();
        // $studentsCount = DB::table('students')->where('promotion', $configuration->promotion)->count();
        $paymentsMonth = new Payment();
        $pyments = $paymentsMonth->paymentByMonth();

        $totalIncomeAndPaymentAmount = $paymentsMonth->totalIncomeAndPaymentAmount();

       

        $teachersCount = DB::table('teachers')->count();
        $studentsCount = DB::table('students')->count();

        $currentMonth = Carbon::now()->month;
                          $currentYear = Carbon::now()->year;
        $ByMonthExpense = Expenses::whereMonth('created_at', $currentMonth)
                               ->whereYear('created_at', $currentYear)
                               ->sum('amount' );

     

       

        return Inertia::render('Dashboard', ['teachersCount' => $teachersCount, 'studentsCount' => $studentsCount,
            'pyments' => $pyments, 'totalIncomeAndPaymentAmount' => $totalIncomeAndPaymentAmount ,'ByMonthExpense'=>$ByMonthExpense ]);
    }

}