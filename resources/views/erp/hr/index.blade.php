@extends('layouts.erp')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1">Ressources Humaines</h2>
    <p class="text-muted mb-0 small">Effectifs, présence et salaires par établissement</p>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        @if($employees->isEmpty())
            <div class="alert alert-info mb-0 rounded-3">Aucun employé enregistré.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Poste</th>
                            <th>Département</th>
                            <th>Hôtel</th>
                            <th>Horaires</th>
                            @can('view_salaries')
                                <th class="text-end">Salaire</th>
                            @endcan
                            <th>Présence</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td class="fw-semibold">{{ $employee->matricule }}</td>
                                <td>{{ $employee->full_name }}</td>
                                <td>{{ $employee->job_title }}</td>
                                <td>{{ $employee->department }}</td>
                                <td>{{ $employee->hotel?->name ?? '—' }}</td>
                                <td class="small">{{ $employee->shift_hours ?? '—' }}</td>
                                @can('view_salaries')
                                    <td class="text-end">{{ number_format($employee->monthly_salary, 0, ',', ' ') }} XOF</td>
                                @endcan
                                <td>
                                    @php
                                        $statusBadge = match($employee->presence_status) {
                                            'En poste' => 'bg-success',
                                            'En congé' => 'bg-warning text-dark',
                                            'Absent' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusBadge }}">{{ $employee->presence_status }}</span>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('erp.hr.toggle', $employee) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill">
                                            Basculer statut
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
