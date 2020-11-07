<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //作成したモデルファイルに$fillableを設定する
     protected $fillable = ['content'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}