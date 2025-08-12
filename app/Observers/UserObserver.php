<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        try {
            // Pastikan table roles tersedia
            if (! Schema::hasTable('roles')) {
                return;
            }

            // Jika user belum punya role, assign 'customer'
            if ($user->roles()->count() === 0) {
                $role = Role::firstOrCreate([
                    'name' => 'customer',
                    'guard_name' => 'web',
                ]);

                $user->assignRole($role);
            }
        } catch (\Throwable $e) {
            // Silent fail, jangan gagalkan registrasi
        }
    }
}
