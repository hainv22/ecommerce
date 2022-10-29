<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeMoneyOwnerHistory extends Model
{
    use HasFactory;
    protected $guarded = [''];
    const ADMIN = 1;
    const CHUNG = 2;
}
