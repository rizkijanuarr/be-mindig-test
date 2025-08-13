<?php

namespace App\Filament\Pages\Auth;

use BetterFuturesStudio\FilamentLocalLogins\Concerns\HasLocalLogins;
use Filament\Pages\Auth\Login;

class AdminLogin extends Login
{
    use HasLocalLogins;
}
