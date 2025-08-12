<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel permission siap
        if (! Schema::hasTable('roles') || ! Schema::hasTable('permissions')) {
            $this->command?->warn('Permissions/roles tables are not ready. Skipping ShieldSeeder.');
            return;
        }

        $guard = 'web';

        // Pastikan roles ada
        $super = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => $guard]);
        $customer = Role::firstOrCreate(['name' => 'customer', 'guard_name' => $guard]);

        // Ambil semua permissions yang tersedia
        $permissions = Permission::query()->get();

        // Jika belum ada permission sama sekali, buat minimal agar akses CMS tidak error
        if ($permissions->isEmpty()) {
            $seedPerms = [
                'access_cms',
            ];
            foreach ($seedPerms as $name) {
                Permission::findOrCreate($name, $guard);
            }
            $permissions = Permission::query()->get();
        }

        // Assign SEMUA permission (sementara) untuk menghindari error di production
        $super->syncPermissions($permissions);
        $customer->syncPermissions($permissions);

        $this->command?->info('ShieldSeeder: roles and permissions assigned (temporary allow-all).');
    }
}
