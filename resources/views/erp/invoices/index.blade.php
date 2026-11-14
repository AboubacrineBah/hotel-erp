@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Facturation</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if ($invoices->isEmpty())
                <div class="alert alert-info mb-0">Aucune facture pour le moment.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ number_format($invoice->total_amount ?? 0, 2, ',', ' ') }} €</td>
                                    <td>{{ $invoice->status ?? 'En attente' }}</td>
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
