<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    /**
     * [用户绑定动态]
     * @Author: PanNiNan
     * @Date  : 2020/6/10
     * @Time  : 8:33
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * [数据流]
     * @Author: PanNiNan
     * @Date  : 2020/6/21
     * @Time  : 18:02
     * @return mixed
     */
    public function feed()
    {
        $userIds = $this->followings()->get()->pluck('id')->toArray();
        array_push($userIds, $this->id);
        return Status::whereIn('user_id', $userIds)->with('user')->orderBy('created_at', 'desc');
    }

    /**
     * [用户粉丝]
     * @Author: PanNiNan
     * @Date  : 2020/6/21
     * @Time  : 16:44
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id',
            'follower_id');
    }

    /**
     * [用户关注人]
     * @Author: PanNiNan
     * @Date  : 2020/6/21
     * @Time  : 16:48
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id',
            'user_id');
    }

    /**
     * [关注操作]
     * @Author: PanNiNan
     * @Date  : 2020/6/21
     * @Time  : 17:03
     * @param $user_ids
     */
    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followers()->sync($user_ids, false);
    }

    /**
     * [取关操作]
     * @Author: PanNiNan
     * @Date  : 2020/6/21
     * @Time  : 17:04
     * @param $user_ids
     */
    public function unFollow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followers()->detach($user_ids);
    }

    public function isFollowing($user_id)
    {
        return $this->followers->contains($user_id);
    }
}
