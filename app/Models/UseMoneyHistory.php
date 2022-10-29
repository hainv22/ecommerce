<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UseMoneyHistory extends Model
{
    use HasFactory;
    protected $guarded = [''];
    const TRA_TRUNG_QUOC = 1;
    const MUA_TRUNG_QUOC = 2;
    const SU_DUNG_TIEN = 3;
}
