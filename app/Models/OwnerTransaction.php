<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerTransaction extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function owner()
    {
        return $this->belongsTo(OwnerChina::class, 'ot_owner_china_id');
    }

    public function detail()
    {
        return $this->hasMany(OwnerTransactionDetail::class, 'otd_owner_transaction_id');
    }

    public function changeMoneyOwnerHistories()
    {
        return $this->hasMany(ChangeMoneyOwnerHistory::class, 'cmh_owner_transaction_id');
    }
}
