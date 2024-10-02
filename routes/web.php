<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ListdattenController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\TeacherHasMatierController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExameController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PrintInformation;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use League\Config\Configuration;
// import Request
use Illuminate\Http\Request;
use App\Models\Configuration as LC;
use App\Models\Payment;
use App\Models\Student;
use App\Models\group;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    if (auth()->check() && auth()->user()->hasRole('admin')) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('students.index');
    }
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('/guardians', GuardianController::class);
    Route::resource('/teachers', TeachersController::class);
    Route::resource('/matieres', MatiereController::class);
    Route::resource('/students', StudentController::class);
    Route::resource('/classes', GroupController::class);
    Route::resource('/filieres', FiliereController::class);
    Route::resource('/levels', LevelController::class);
    Route::resource('/locales', LocaleController::class);
    Route::resource('/departements', DepartementController::class);
    Route::resource('/payments', PaymentController::class);
    Route::resource('/configurations', ConfigurationController::class);
    Route::post('/student/{student_id}/new-payment', [StudentController::class, 'newPayment'])->name('new_payment');
    Route::resource('/absence', AbsenceController::class);
    Route::resource('/teacherHasMatier', TeacherHasMatierController::class);
    Route::resource('/seance', SeanceController::class);
    Route::resource('/expenses', ExpensesController::class);
    Route::resource('/incomes', IncomeController::class);
    Route::resource('/notes', NoteController::class);
    Route::resource('/listdatten',ListdattenController::class);
    Route::get('/indexprof',[TeachersController::class, 'indexProf']);
    Route::resource('/roles', RolesController::class);
    Route::resource('/printInformation', PrintInformation::class);
    Route::resource('/users', UserController::class); 


    Route::get('/ get-notestudent-byseanceID/{studentId}/{seanceId}', [NoteController::class, 'getNotebyStudentbySence']);
    Route::get('/getnote-bySeance/{studentId}/{seanceId}', [NoteController::class, 'getNotebybySence']);

    Route::get('/update-Active-Users/{id}/{data}', [UserController::class, 'updateActiveUsers']);
    Route::get('/users/{role?}', [UserController::class, 'index']);
   
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/permissions', 'PermissionController@index');

    Route::resource('/exams', ExameController::class);
    Route::post('/exams/{examId}/add-note', [ExameController::class, 'storeNote']);
    Route::get('/permissions', 'PermissionController@index');

    Route::get('/get-matier-by-class/{id}', [ExameController::class, 'getMatierByGroupname']);
    Route::resource('/exams', ExameController::class);
    Route::post('/exams/{examId}/add-note', [ExameController::class, 'storeNote']);
    Route::get('/permissions', 'PermissionController@index');


    Route::get('/get-student-by-group/{id}', [ExameController::class, 'getStudent']);
    Route::get('/get-note-student-by-group/{id}', [ExameController::class, 'getNoteStudent']);

    Route::get('/generate-pdf-inscription/{id}', [PDFController::class, 'generatePDF_inscription']);
    Route::get('/generate-pdf-serie/{array}/{id}', [PDFController::class, 'generatePDF_Serie']);
    Route::get('/generate-pdf-revenu/{id}', [PDFController::class, 'generatePDF_Revenu']);

    Route::get('/get-matier-by-group/{id}', [AbsenceController::class, 'getMatierByGroup']);
    Route::get('/get-seance-by-group/{groupId}', [AbsenceController::class, 'getSeancesByGroup']);
    Route::get('/get-Student-by-group/{id}', [SeanceController::class, 'getStudentsByGroup']);
    Route::get('/get-local-by-id/{id}', [SeanceController::class, 'getLocal']);
    Route::get('/get-absences-by-seance/{id}', [SeanceController::class, 'getAbsencesBySeance']);
    Route::delete('/absence/{student_id}/{seance_id}', [AbsenceController::class, 'destroy']);

    /* ! --- ! */
    Route::get('/get-group-by-level/{id}', [StudentController::class, 'getClassByLevel']);
    Route::get('/check-student-exist/{id}', [StudentController::class, 'checkIfStudentExists']);
    Route::get('/check-parent-exist/{id}', [StudentController::class, 'checkIfParentExists']);
    Route::get('/filter-student/{id}', [StudentController::class, 'filteredStudents']);
    Route::get('/students?page=/{id}', [StudentController::class, 'student']);
    Route::get('/students-Not-payement', [StudentController::class, 'student']);

    Route::get('/absencesByMonth/{id}/{date}', [AbsenceController::class, 'absencesByMonth']);
    
    Route::get('/noteByGroupYear/{id}', [AbsenceController::class, 'getStudentNotesPDF']);

    Route::put('/increment-level', [StudentController::class, 'incrementStudentLevel']);
});


