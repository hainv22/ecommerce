<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UseMoneyHistory extends Model
{
    use HasFactory;
    protected $guarded = [''];
    const SU_DUNG_TIEN = 1;
    const TRA_TRUNG_QUOC = 2;
    const MUA_BANG_DINH = 3;
    const MUA_THUNG_GIAY = 4;
    const TRA_TIEN_DAU_BAO_HN_BN = 5;
    const TRA_TIEN_VAN_CHUYEN_TQ_HN = 6;
    const TIEN_VE = 7;
}
