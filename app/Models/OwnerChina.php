<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerChina extends Model
{
    use HasFactory;
    protected $guarded = [''];
    protected $table = 'owner_china';

    public function ownerTransactions()
    {
        return $this->hasMany(OwnerTransaction::class, 'ot_owner_china_id');
    }
}
