@extends('layouts.erp')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-1">Gestion des Comptes Utilisateurs & Privilèges (RBAC)</h4>
            <p class="text-muted small mb-4">Créez de nouveaux collaborateurs, modifiez les rôles à chaud ou suspendez un
                accès.</p>

            <!-- Formulaire Création Compte -->
            <form action="{{ route('erp.admin.users.store') }}" method="POST" class="row g-3 bg-light p-3 rounded-3 mb-4">
                @csrf
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nom Complet *" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Email connexion *" required>
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="hotel_assignment" class="form-control" value="Tous les hôtels" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="fa-solid fa-user-plus me-1"></i>Créer
                    </button>
                </div>
            </form>

            <!-- Tableau des Utilisateurs -->
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th>Utilisateur</th>
                            <th>Rôle Attribué</th>
                            <th>Affectation</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>
                                    <strong>{{ $u->name }}</strong><br>
                                    <small class="text-muted">{{ $u->email }}</small>
                                </td>
                                <td>
                                    <form action="{{ route('erp.admin.users.role', $u) }}" method="POST" class="d-flex gap-2">
                                        @csrf @method('PATCH')
                                        <select name="role" class="form-select form-select-sm w-auto"
                                            onchange="this.form.submit()">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ $u->hasRole($role->name) ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $u->hotel_assignment }}</td>
                                <td>
                                    <span class="badge {{ $u->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $u->is_active ? 'Actif' : 'Suspendu' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('erp.admin.users.toggle', $u) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                {{ $u->is_active ? 'Suspendre' : 'Activer' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
