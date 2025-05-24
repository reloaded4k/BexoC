<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingUpdate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'shipment_id',
        'status',
        'notes',
    ];

    /**
     * Get the shipment that owns the tracking update
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}