<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, Video $video): bool
    {
        return $user->id === $video->channel->user_id;
    }
}
