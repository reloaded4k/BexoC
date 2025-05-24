<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shipment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tracking_number',
        'sender_name',
        'sender_email',
        'sender_address',
        'sender_contact',
        'recipient_name',
        'recipient_email',
        'recipient_address',
        'recipient_contact',
        'cargo_name',
        'cargo_description',
        'cargo_length',
        'cargo_width',
        'cargo_height',
        'cargo_weight',
        'current_status',
    ];

    /**
     * Get the tracking updates for the shipment
     */
    public function trackingUpdates()
    {
        return $this->hasMany(TrackingUpdate::class);
    }

    /**
     * Generate a unique tracking number
     */
    public static function generateTrackingNumber()
    {
        $prefix = "BX";
        $random = strtoupper(Str::random(8));
        
        // Ensure tracking number is unique
        while (self::where('tracking_number', $prefix . $random)->exists()) {
            $random = strtoupper(Str::random(8));
        }
        
        return $prefix . $random;
    }

    /**
     * Add a tracking update to this shipment
     */
    public function addTrackingUpdate($status, $notes)
    {
        $update = new TrackingUpdate([
            'status' => $status,
            'notes' => $notes
        ]);
        
        $this->trackingUpdates()->save($update);
        $this->current_status = $status;
        $this->save();
        
        return $update;
    }

    /**
     * Get the latest tracking update
     */
    public function latestUpdate()
    {
        return $this->trackingUpdates()->latest()->first();
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate tracking number when creating a new shipment
        static::creating(function ($shipment) {
            if (!$shipment->tracking_number) {
                $shipment->tracking_number = self::generateTrackingNumber();
            }
        });
    }
}