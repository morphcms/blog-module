<?php

namespace Modules\Blog\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Blog\Enums\PostPermission;
use Modules\Blog\Enums\PostStatus;
use Modules\Blog\Models\Post;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user)
    {
        return ! $user || $user->canAny([PostPermission::ViewAny->value, PostPermission::ViewOwned->value]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Post $post)
    {
        if (! $user) {
            return PostStatus::from($post->status) === PostStatus::Published;
        }

        if ($post->isOwnedBy($user)) {
            return true;
        }

        return $user->can(PostPermission::View->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->can(PostPermission::Create->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post)
    {
        if ($post->isOwnedBy($user)) {
            return true;
        }

        return $user->can(PostPermission::Update->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post)
    {
        if ($post->isOwnedBy($user)) {
            return true;
        }

        return $user->can(PostPermission::Delete->value);
    }

    public function replicate(User $user, Post $post)
    {
        if ($post->isOwnedBy($user)) {
            return true;
        }

        return $user->can(PostPermission::Replicate->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post)
    {
        if ($post->isOwnedBy($user)) {
            return true;
        }

        return $user->can(PostPermission::Restore->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post)
    {
        if ($post->isOwnedBy($user)) {
            return true;
        }

        return $user->can(PostPermission::Delete->value);
    }
}
