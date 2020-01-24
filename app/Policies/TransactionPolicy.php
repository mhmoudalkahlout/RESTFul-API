<?php

namespace App\Policies;

use App\Transaction;
use App\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view transactions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->buyer->id OR $user->id === $transaction->product->seller->id;
    }
}
