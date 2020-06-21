<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    /**
     * [用户更新权限策略]
     * @Author: PanNiNan
     * @Date  : 2020/6/7
     * @Time  : 22:30
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    /**
     * [删除用户授权]
     * @Author: PanNiNan
     * @Date  : 2020/6/21
     * @Time  : 17:47
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }

    /**
     * [关注用户授权]
     * @Author: PanNiNan
     * @Date  : 2020/6/21
     * @Time  : 17:47
     * @param User $currentUserr
     * @param User $user
     * @return bool
     */
    public function follow(User $currentUserr, User $user)
    {
        return $currentUserr !== $user;
    }
}
