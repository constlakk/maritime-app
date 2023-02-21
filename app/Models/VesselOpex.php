<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VesselOpex extends Model
{
    use HasFactory;

    protected $fillable = [

        'vessel_id',
        'date',
        'expenses'

    ];

    public function vessel() {

        return $this->belongsTo(Vessel::class);

    }
}
