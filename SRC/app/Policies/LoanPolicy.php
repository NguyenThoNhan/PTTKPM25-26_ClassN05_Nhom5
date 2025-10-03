<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    public function updateDocument(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id 
            && $loan->status === 'borrowed'
            && $loan->book->type === 'online';
    }
}