<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Http\Requests\ShipmentRequest;
use App\Mail\ShipmentCreated;
use Illuminate\Support\Facades\Mail;

class ShipmentController extends Controller
{
    /**
     * Display the shipment booking form.
     */
    public function create()
    {
        return view('shipments.create');
    }

    /**
     * Store a newly created shipment in the database.
     */
    public function store(ShipmentRequest $request)
    {
        // Create the shipment with validated data
        $shipment = Shipment::create($request->validated());
        
        // Add initial tracking update
        $shipment->addTrackingUpdate('Booking Received', 'Shipment booking has been received and is being processed.');
        
        // Send confirmation email
        try {
            Mail::to($shipment->sender_email)
                ->send(new ShipmentCreated($shipment));
        } catch (\Exception $e) {
            // Log the error but continue
            \Log::error('Failed to send shipment email: ' . $e->getMessage());
        }
        
        // Redirect to the confirmation page
        return redirect()->route('shipments.confirmation', $shipment->tracking_number)
            ->with('success', 'Shipment booked successfully.');
    }

    /**
     * Display the shipment confirmation page.
     */
    public function confirmation($trackingNumber)
    {
        $shipment = Shipment::where('tracking_number', $trackingNumber)->firstOrFail();
        return view('shipments.confirmation', compact('shipment'));
    }

    /**
     * Display the tracking form.
     */
    public function trackingForm()
    {
        return view('shipments.track');
    }

    /**
     * Track a shipment by tracking number.
     */
    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string'
        ]);
        
        $trackingNumber = $request->tracking_number;
        $shipment = Shipment::where('tracking_number', $trackingNumber)->first();
        
        if (!$shipment) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['tracking_number' => 'No shipment found with that tracking number.']);
        }
        
        return view('shipments.tracking_result', compact('shipment'));
    }
}