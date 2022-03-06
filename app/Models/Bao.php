<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bao extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function transport()
    {
        return $this->belongsTo(Transport::class, 'b_transport_id');
    }
}
