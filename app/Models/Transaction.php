<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function User()
    {
        return parent::belongsTo(User::class, "user_id");
    }

    public function borroweds()
    {
        return parent::hasOne(Borrowed::class, "borrowed_id");
    }
}
