<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowed extends Model
{
    protected $guarded = [];

    public function Users()
    {
        return parent::belongsTo(User::class, "user_id");
    }

    public function Helm()
    {
        return parent::belongsTo(Helm::class, "helmet_id");
    }

    public function transactions()
    {
        return parent::hasOne(Transaction::class);
    }
}
