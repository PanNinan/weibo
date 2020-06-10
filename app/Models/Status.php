<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * [动态绑定用户]
     * @Author: PanNiNan
     * @Date  : 2020/6/10
     * @Time  : 8:32
     */
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
