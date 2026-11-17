@extends('layouts.erp')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1">Stock & Économat</h2>
    <p class="text-muted mb-0 small">Suivi des inventaires et alertes de seuil</p>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        @if($stockItems->isEmpty())
            <div class="alert alert-info mb-0 rounded-3">Aucun article en stock.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SKU</th>
                            <th>Article</th>
                            <th>Catégorie</th>
                            <th>Hôtel</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-center">Seuil alerte</th>
                            <th class="text-end">Prix unitaire</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockItems as $item)
                            @php $isLow = $item->quantity <= $item->alert_threshold; @endphp
                            <tr class="{{ $isLow ? 'table-warning' : '' }}">
                                <td class="fw-semibold">{{ $item->sku }}</td>
                                <td>{{ $item->name }}</td>
                                <td><span class="badge bg-secondary">{{ $item->category }}</span></td>
                                <td>{{ $item->hotel?->name ?? '—' }}</td>
                                <td class="text-center fw-bold {{ $isLow ? 'text-danger' : '' }}">
                                    {{ $item->quantity }}
                                    @if($isLow)
                                        <i class="fa-solid fa-triangle-exclamation text-danger ms-1" title="Stock bas"></i>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->alert_threshold }}</td>
                                <td class="text-end">{{ number_format($item->unit_price, 0, ',', ' ') }} XOF</td>
                                <td class="text-end">
                                    <form action="{{ route('erp.stock.restock', $item) }}" method="POST" class="d-inline-flex gap-1">
                                        @csrf
                                        <input type="number" name="quantity_to_add" value="20" min="1" max="1000"
                                               class="form-control form-control-sm" style="width: 70px">
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill">
                                            <i class="fa-solid fa-plus"></i> Réappro
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
