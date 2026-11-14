<?php

namespace App\Http\Controllers\ERP;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class HrEmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('hotel')->orderBy('full_name')->get();
        $canViewSalaries = Auth::user()->can('view_salaries');

        return view('erp.hr.index', compact('employees', 'canViewSalaries'));
    }

    public function toggleStatus(Employee $employee)
    {
        $newStatus = $employee->presence_status === 'En poste' ? 'En congé' : 'En poste';
        $employee->update(['presence_status' => $newStatus]);

        return back()->with('success', "Statut RH de {$employee->full_name} mis à jour : {$newStatus}.");
    }
}
