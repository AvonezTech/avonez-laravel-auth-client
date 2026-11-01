<?php

namespace Avonez\AuthClient\Models\Traits;

trait BelongsToUser
{
    public function initializeBelongsToUser()
    {
        $this->fillable[] = 'user_id';
    }

    public function user()
    {
        $userModel = config('avonez-auth-client.user-model');
        return $this->belongsTo($userModel, 'user_id');
    }
}