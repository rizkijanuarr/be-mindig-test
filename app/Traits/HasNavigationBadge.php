<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait HasNavigationBadge
{
    public static function getNavigationBadge(): ?string
    {
        try {
            $user = Auth::user();
            $modelClass = static::$model;
            $query = $modelClass::query();

            if ($user && ! $user->hasRole('super_admin')) {
                // Filter only if model has user_id column
                $modelInstance = new $modelClass;
                $table = $modelInstance->getTable();
                if (Schema::hasColumn($table, 'user_id')) {
                    $query->where('user_id', $user->id);
                }
            }

            return (string) $query->count();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
