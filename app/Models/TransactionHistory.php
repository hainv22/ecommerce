<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'th_transaction_id');
    }
}
