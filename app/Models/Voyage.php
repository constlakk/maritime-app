<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voyage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vessel_id',
        'code',
        'start',
        'end',
        'status',
        'revenues',
        'expenses',
        'profit'
    ];

    public function vessel() {

        return $this->belongsTo(Vessel::class);

    }
}
