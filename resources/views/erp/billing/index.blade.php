@extends('layouts.erp')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1">Facturation</h2>
    <p class="text-muted mb-0 small">Liste des factures émises</p>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        @if($invoices->isEmpty())
            <div class="alert alert-info mb-0 rounded-3">Aucune facture pour le moment.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>N° Facture</th>
                            <th>Client</th>
                            <th>Hôtel / Chambre</th>
                            <th>Date</th>
                            <th class="text-end">HT</th>
                            <th class="text-end">TVA</th>
                            <th class="text-end">TTC</th>
                            <th>Paiement</th>
                            <th class="text-end">PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td class="fw-semibold">{{ $invoice->invoice_number }}</td>
                                <td>
                                    {{ $invoice->booking?->client_name ?? '—' }}
                                    <div class="small text-muted">{{ $invoice->booking?->client_email }}</div>
                                </td>
                                <td>
                                    {{ $invoice->booking?->hotel?->name ?? '—' }}
                                    <div class="small text-muted">{{ $invoice->booking?->room?->name ?? '—' }}</div>
                                </td>
                                <td class="small">{{ $invoice->issued_at?->format('d/m/Y H:i') }}</td>
                                <td class="text-end">{{ number_format($invoice->amount_ht, 0, ',', ' ') }}</td>
                                <td class="text-end">{{ number_format($invoice->tva_amount, 0, ',', ' ') }}</td>
                                <td class="text-end fw-bold">{{ number_format($invoice->amount_ttc, 0, ',', ' ') }} XOF</td>
                                <td>
                                    @php
                                        $payBadge = match($invoice->payment_status) {
                                            'Payée' => 'bg-success',
                                            'En attente' => 'bg-warning text-dark',
                                            'Annulée' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $payBadge }}">{{ $invoice->payment_status }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('invoices.pdf', $invoice->invoice_number) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill" target="_blank">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
