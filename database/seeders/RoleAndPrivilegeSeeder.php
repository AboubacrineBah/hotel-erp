<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPrivilegeSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Création des Permissions
        $permissions = [
            'access_erp',
            'view_revenue',
            'manage_reservations',
            'manage_billing',
            'manage_stock',
            'manage_hr',
            'view_salaries',
            'manage_users_rbac',
            'vip_nomad_access',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Création des 5 Rôles & Attribution des Privilèges
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $reception = Role::firstOrCreate(['name' => 'reception']);
        $reception->givePermissionTo(['access_erp', 'manage_reservations', 'manage_billing']);

        $stock = Role::firstOrCreate(['name' => 'stock']);
        $stock->givePermissionTo(['access_erp', 'manage_stock']);

        $hr = Role::firstOrCreate(['name' => 'hr']);
        $hr->givePermissionTo(['access_erp', 'manage_hr', 'view_salaries']);

        $vipClient = Role::firstOrCreate(['name' => 'vip_client']);
        $vipClient->givePermissionTo(['vip_nomad_access']);

        // 3. Création des Comptes Utilisateurs Démo
        $users = [
            [
                'name' => 'Moussa Keïta',
                'email' => 'dg@azalai.com',
                'phone' => '+223 70 00 00 01',
                'hotel_assignment' => 'Tous les hôtels',
                'nomad_points' => 45000,
                'role' => 'super_admin',
            ],
            [
                'name' => 'Fatou Ndiaye',
                'email' => 'reception@azalai.com',
                'phone' => '+225 07 00 00 02',
                'hotel_assignment' => 'Abidjan',
                'nomad_points' => 0,
                'role' => 'reception',
            ],
            [
                'name' => 'Ousmane Bamba',
                'email' => 'stock@azalai.com',
                'phone' => '+223 76 00 00 03',
                'hotel_assignment' => 'Bamako',
                'nomad_points' => 0,
                'role' => 'stock',
            ],
            [
                'name' => 'Kadiatou Traoré',
                'email' => 'rh@azalai.com',
                'phone' => '+221 77 00 00 04',
                'hotel_assignment' => 'Dakar',
                'nomad_points' => 0,
                'role' => 'hr',
            ],
            [
                'name' => 'Amadou Diallo',
                'email' => 'amadou.diallo@vip.com',
                'phone' => '+223 66 00 00 05',
                'hotel_assignment' => 'Membre Nomad Gold',
                'nomad_points' => 14500,
                'role' => 'vip_client',
            ],
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('Hotel2026'),
                    'phone' => $u['phone'],
                    'hotel_assignment' => $u['hotel_assignment'],
                    'nomad_points' => $u['nomad_points'],
                    'is_active' => true,
                ]
            );
            $user->assignRole($u['role']);
        }
    }
}
