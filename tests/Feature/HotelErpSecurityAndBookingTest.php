<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\HotelErpSeeder;
use Database\Seeders\RoleAndPrivilegeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelErpSecurityAndBookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPrivilegeSeeder::class);
        $this->seed(HotelErpSeeder::class);
    }

    /** @test */
    public function public_visitor_can_view_home_and_cannot_access_erp(): void
    {
        // 1. Le visiteur public accède à la vitrine sans boutons ERP
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('HOTEL');
        $response->assertDontSee('Portail ERP');

        // 2. Tentative d'accès direct au dashboard ERP bloquée (redirection login)
        $this->get('/erp/dashboard')->assertRedirect('/login');
    }

    /** @test */
    public function reception_role_can_access_reservations_but_is_forbidden_from_hr_and_admin(): void
    {
        $receptionUser = User::where('email', 'reception@azalai.com')->firstOrFail();

        // Autorisé sur Réservations & Facturation
        $this->actingAs($receptionUser)
            ->get('/erp/reservations')
            ->assertStatus(200);

        // Interdit (403 Forbidden) sur RH et Administration RBAC
        $this->actingAs($receptionUser)
            ->get('/erp/rh')
            ->assertStatus(403);

        $this->actingAs($receptionUser)
            ->get('/erp/admin/privileges')
            ->assertStatus(403);
    }

    /** @test */
    public function super_admin_can_access_all_modules_and_suspend_an_account(): void
    {
        $superAdmin = User::where('email', 'dg@azalai.com')->firstOrFail();
        $stockUser = User::where('email', 'stock@azalai.com')->firstOrFail();

        // Accès au module Admin & Privilèges
        $this->actingAs($superAdmin)
            ->get('/erp/admin/privileges')
            ->assertStatus(200);

        // Suspension du compte Stock
        $this->actingAs($superAdmin)
            ->patch("/erp/admin/users/{$stockUser->id}/toggle-active")
            ->assertRedirect();

        $this->assertFalse($stockUser->fresh()->is_active);
    }

    /** @test */
    public function public_booking_creates_reservation_decrements_room_and_calculates_18_percent_tva(): void
    {
        $hotel = Hotel::where('slug', 'abidjan')->firstOrFail();
        $room = Room::where('hotel_id', $hotel->id)->firstOrFail();
        $initialStock = $room->available_count;

        $response = $this->post('/bookings/store', [
            'hotel_id' => $hotel->id,
            'room_id' => $room->id,
            'client_name' => 'Awa Traoré',
            'client_email' => 'awa.traore@exemple.ml',
            'client_phone' => '+223 70 11 22 33',
            'check_in' => now()->addDays(2)->format('Y-m-d'),
            'check_out' => now()->addDays(4)->format('Y-m-d'), // 2 nuits
            'guests_summary' => '2 Adultes · 1 Chambre',
            'extra_option' => 'airport', // +15 000 XOF
        ]);

        $response->assertRedirect();

        // Vérification décrémentation chambre
        $this->assertEquals($initialStock - 1, $room->fresh()->available_count);

        // Vérification calcul TTC = (90 000 * 2) + 15 000 = 195 000 XOF
        $this->assertDatabaseHas('bookings', [
            'client_email' => 'awa.traore@exemple.ml',
            'nights' => 2,
            'total_ttc' => 195000.00,
        ]);

        $this->assertDatabaseHas('invoices', [
            'amount_ttc' => 195000.00,
            'tva_rate' => 18.00,
            'payment_status' => 'Payée',
        ]);
    }
}
