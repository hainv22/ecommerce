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
}
