<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\StockItem;
use Illuminate\Database\Seeder;

class HotelErpSeeder extends Seeder
{
    public function run(): void
    {
        $abidjan = Hotel::firstOrCreate(['slug' => 'abidjan'], [
            'name' => 'Hôtel Abidjan',
            'city' => 'Abidjan',
            'country' => 'Côte d\'Ivoire',
            'badge' => '4★ Business & Spa',
            'address' => 'Boulevard, Abidjan',
            'description' => 'Au cœur du quartier dynamique, piscine panoramique, spa et gastronomie.',
            'min_price' => 90000,
            'image_url' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=900&q=80',
        ]);

        $bamako = Hotel::firstOrCreate(['slug' => 'bamako'], [
            'name' => 'Hôtel Bamako',
            'city' => 'Bamako',
            'country' => 'Mali',
            'badge' => '5★ Flagship Historique',
            'address' => 'Avenue, Quartier Hotel — Bamako',
            'description' => 'Le fleuron historique des hotels à Bamako.',
            'min_price' => 75000,
            'image_url' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=900&q=80',
        ]);

        // Chambres
        Room::firstOrCreate(['hotel_id' => $abidjan->id, 'name' => 'Chambre Supérieure Business'], [
            'size' => '32 m²',
            'capacity' => 2,
            'price_per_night' => 90000,
            'available_count' => 5,
            'perks' => ['Wifi Fibre', 'Petit-déjeuner inclus', 'Espace Bureau'],
            'image_url' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=80',
        ]);

        Room::firstOrCreate(['hotel_id' => $bamako->id, 'name' => 'Suite Junior Djoliba'], [
            'size' => '52 m²',
            'capacity' => 3,
            'price_per_night' => 135000,
            'available_count' => 3,
            'perks' => ['Terrasse Privée', 'Coin Salon', 'Accès Spa & Fitness'],
            'image_url' => 'https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=800&q=80',
        ]);

        // Stocks
        StockItem::firstOrCreate(['sku' => 'STK-01'], [
            'hotel_id' => $abidjan->id,
            'name' => 'Eau Minérale Gazeuse (33cl)',
            'category' => 'Minibar',
            'quantity' => 18,
            'alert_threshold' => 25,
            'unit_price' => 1500,
        ]);

        StockItem::firstOrCreate(['sku' => 'STK-02'], [
            'hotel_id' => $bamako->id,
            'name' => 'Jus de Bissap Artisanal Bio',
            'category' => 'Restaurant & Bar',
            'quantity' => 42,
            'alert_threshold' => 20,
            'unit_price' => 2500,
        ]);

        // Employés RH
        Employee::firstOrCreate(['matricule' => 'EMP-01'], [
            'hotel_id' => $bamako->id,
            'full_name' => 'Moussa Traoré',
            'job_title' => 'Chef de Réception',
            'department' => 'Front-Office',
            'shift_hours' => '07h00 - 15h00',
            'monthly_salary' => 450000,
            'presence_status' => 'En poste',
        ]);

        Employee::firstOrCreate(['matricule' => 'EMP-02'], [
            'hotel_id' => $abidjan->id,
            'full_name' => 'Clarisse Kouassi',
            'job_title' => 'Gouvernante Générale',
            'department' => 'Étages & Spa',
            'shift_hours' => '08h00 - 16h30',
            'monthly_salary' => 420000,
            'presence_status' => 'En poste',
        ]);
    }
}
