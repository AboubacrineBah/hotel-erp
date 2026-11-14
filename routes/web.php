<?php

use App\Http\Controllers\ERP\DashboardController;
use App\Http\Controllers\ERP\HrEmployeeController;
use App\Http\Controllers\ERP\InvoiceController;
use App\Http\Controllers\ERP\ReservationCrmController;
use App\Http\Controllers\ERP\StockController;
use App\Http\Controllers\ERP\UserPrivilegeController;
use App\Http\Controllers\Public\BookingPublicController;
use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ==================== 1. ESPACE VISITEUR PUBLIC ====================
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/api/hotels/{hotel:slug}/rooms', [HomeController::class, 'roomsByHotel'])->name('public.hotel.rooms');
Route::post('/bookings/store', [BookingPublicController::class, 'store'])->name('public.bookings.store');
Route::get('/invoices/{invoice:invoice_number}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');

// Routes d'authentification Laravel UI
Auth::routes();

// ==================== 2. ESPACE CLIENT CONNECTÉ (VIP NOMAD) ====================
Route::middleware(['auth', 'active.account'])->group(function () {
    Route::get('/mes-sejours', [BookingPublicController::class, 'myBookings'])->name('client.bookings');
});

// ==================== 3. PORTAIL ERP HÔTELIER (PROTÉGÉ PAR PRIVILÈGES RBAC) ====================
Route::prefix('erp')
    ->name('erp.')
    ->middleware(['auth', 'active.account', 'permission:access_erp'])
    ->group(function () {

        // Tableau de bord central (KPIs filtrés selon rôle)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Module Réservations & CRM (Super Admin + Réceptionniste)
        Route::middleware('permission:manage_reservations')->group(function () {
            Route::get('/reservations', [ReservationCrmController::class, 'index'])->name('reservations.index');
            Route::patch('/reservations/{booking}/checkin', [ReservationCrmController::class, 'checkIn'])->name('reservations.checkin');
        });

        // Module Facturation (Super Admin + Réceptionniste)
        Route::middleware('permission:manage_billing')->group(function () {
            Route::get('/facturation', [InvoiceController::class, 'index'])->name('invoices.index');
        });

        // Module Stock & Économat (Super Admin + Gestionnaire Stock)
        Route::middleware('permission:manage_stock')->group(function () {
            Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
            Route::post('/stock/{stockItem}/restock', [StockController::class, 'restock'])->name('stock.restock');
        });

        // Module Ressources Humaines (Super Admin + Responsable RH)
        Route::middleware('permission:manage_hr')->group(function () {
            Route::get('/rh', [HrEmployeeController::class, 'index'])->name('hr.index');
            Route::patch('/rh/{employee}/toggle-status', [HrEmployeeController::class, 'toggleStatus'])->name('hr.toggle');
        });

        // Module Administration & Privilèges RBAC (Super Admin uniquement)
        Route::middleware('permission:manage_users_rbac')->group(function () {
            Route::get('/admin/privileges', [UserPrivilegeController::class, 'index'])->name('admin.privileges');
            Route::post('/admin/users', [UserPrivilegeController::class, 'store'])->name('admin.users.store');
            Route::patch('/admin/users/{user}/role', [UserPrivilegeController::class, 'updateRole'])->name('admin.users.role');
            Route::patch('/admin/users/{user}/toggle-active', [UserPrivilegeController::class, 'toggleActive'])->name('admin.users.toggle');
        });
    });
