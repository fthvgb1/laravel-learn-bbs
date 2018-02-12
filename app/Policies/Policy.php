<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function before(User $user, $ability)
    {
        if ($user->can('manage_contents')) {
            return true;
        }
    }
}
