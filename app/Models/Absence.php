<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Absence extends Model
{
    use HasFactory;

    protected $table = 'absences';

    protected $fillable = [
        'date',
        'list_Absence',
        'group_id',
        'seance_id',
    ];

    protected $casts = [
        'list_Absence' => 'array'
    ]; 
    
    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class, 'seance_id');
    }
    public function group(): BelongsTo
    {
        return $this->belongsTo(group::class, 'group_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function absencesByMonth($groupId, $dateString)
    {
        // Parse the provided date string
        $date = Carbon::parse($dateString);
        $year = $date->year;
        $month = $date->month;
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
    
        // Retrieve absences for the specified group and date range
        $absences = Absence::where('group_id', $groupId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
        Log::debug("absences1: ", [$absences]);
    
        // Retrieve students in the specified group
        $studentsInGroup = Student::where('class_id', $groupId)->get();
        Log::debug("studentsInGroup1: ", [$studentsInGroup]);
    
        // Initialize the absence data structure for each student
        $absencesGroupedByStudent = $studentsInGroup->mapWithKeys(function ($student) use ($startDate) {
            $daysInMonth = $startDate->daysInMonth;
            $absenceData = [
                'id' => $student->id,
                'name' => $student->name,
                'absences' => array_fill(1, $daysInMonth, 0),  // one absence count per day
                'total' => 0,
            ];
            return [$student->id => $absenceData];
        });
    
        // Process each absence record
        foreach ($absences as $absence) {
            foreach ($absence->list_Absence as $absentRecord) {
                $absenceDate = Carbon::parse($absence->date);
                $dayOfMonth = $absenceDate->day;
                $studentId = $absentRecord['id'];  // Assuming 'id' is the student ID
                $absencesGroupedByStudentArray = $absencesGroupedByStudent->toArray();
    
                // Increment the absence count for the specific day and student
                $absencesGroupedByStudentArray[$studentId]['absences'][$dayOfMonth] += 1;
                $absencesGroupedByStudentArray[$studentId]['total'] += 1;
    
                // Update the collection
                $absencesGroupedByStudent = collect($absencesGroupedByStudentArray);
            }
        }
    
        Log::debug("Absence2: ", [$absencesGroupedByStudent]);
    
        return $absencesGroupedByStudent;
    }
    
}
