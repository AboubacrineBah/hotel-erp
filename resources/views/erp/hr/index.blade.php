@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Ressources humaines</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($employees->isEmpty())
                <div class="alert alert-info mb-0">Aucun employé enregistré.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $employee->is_active ? 'success' : 'secondary' }}">
                                            {{ $employee->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
