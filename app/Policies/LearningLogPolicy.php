<?php

namespace App\Policies;

use App\Models\LearningLog;
use App\Models\User;

class LearningLogPolicy
{
    public function view(User $user, LearningLog $log): bool
    {
        return $log->user_id === $user->id;
    }

    public function update(User $user, LearningLog $log): bool
    {
        return $log->user_id === $user->id;
    }

    public function delete(User $user, LearningLog $log): bool
    {
        return $log->user_id === $user->id;
    }
}
