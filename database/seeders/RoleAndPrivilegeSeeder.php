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
    /**
     * Mot de passe unique pour TOUS les comptes de démonstration.
     * Documenté partout : Hotel2026
     */
    public const DEMO_PASSWORD = 'Hotel2026';

    public function run(): void
    {
        // Vider le cache des permissions Spatie (source fréquente de bugs)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Permissions
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
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Rôles + attribution
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $reception = Role::firstOrCreate(['name' => 'reception', 'guard_name' => 'web']);
        $reception->syncPermissions(['access_erp', 'manage_reservations', 'manage_billing']);

        $stock = Role::firstOrCreate(['name' => 'stock', 'guard_name' => 'web']);
        $stock->syncPermissions(['access_erp', 'manage_stock']);

        $hr = Role::firstOrCreate(['name' => 'hr', 'guard_name' => 'web']);
        $hr->syncPermissions(['access_erp', 'manage_hr', 'view_salaries']);

        $vipClient = Role::firstOrCreate(['name' => 'vip_client', 'guard_name' => 'web']);
        $vipClient->syncPermissions(['vip_nomad_access']);

        // 3. Comptes démo — on FORCE la mise à jour du mot de passe et de is_active
        //    (firstOrCreate seul ne met pas à jour si l'utilisateur existe déjà)
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
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make(self::DEMO_PASSWORD),
                    'phone' => $u['phone'],
                    'hotel_assignment' => $u['hotel_assignment'],
                    'nomad_points' => $u['nomad_points'],
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Réattribue le rôle (évite les doublons)
            $user->syncRoles([$u['role']]);
        }

        $this->command?->info('Comptes démo prêts. Mot de passe unique : '.self::DEMO_PASSWORD);
    }
}
