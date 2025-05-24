<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Contact;
use App\Http\Requests\TrackingUpdateRequest;
use App\Http\Requests\ShipmentUpdateRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display admin dashboard.
     */
    public function dashboard()
    {
        $totalShipments = Shipment::count();
        $recentShipments = Shipment::latest()->take(5)->get();
        $pendingShipments = Shipment::where('current_status', 'Booking Received')->count();
        $inTransitShipments = Shipment::where('current_status', 'In Transit')->count();
        
        return view('admin.dashboard', compact(
            'totalShipments', 
            'recentShipments', 
            'pendingShipments', 
            'inTransitShipments'
        ));
    }

    /**
     * Display all shipments.
     */
    public function shipments()
    {
        $shipments = Shipment::latest()->paginate(15);
        return view('admin.shipments.index', compact('shipments'));
    }

    /**
     * Display a specific shipment.
     */
    public function showShipment($trackingNumber)
    {
        $shipment = Shipment::where('tracking_number', $trackingNumber)->firstOrFail();
        return view('admin.shipments.show', compact('shipment'));
    }

    /**
     * Show the form for editing a shipment.
     */
    public function editShipment($trackingNumber)
    {
        $shipment = Shipment::where('tracking_number', $trackingNumber)->firstOrFail();
        return view('admin.shipments.edit', compact('shipment'));
    }

    /**
     * Update the specified shipment.
     */
    public function updateShipment(ShipmentUpdateRequest $request, $trackingNumber)
    {
        $shipment = Shipment::where('tracking_number', $trackingNumber)->firstOrFail();
        $shipment->update($request->validated());
        
        return redirect()->route('admin.shipments.show', $shipment->tracking_number)
            ->with('success', 'Shipment updated successfully.');
    }

    /**
     * Delete the specified shipment.
     */
    public function destroyShipment($trackingNumber)
    {
        $shipment = Shipment::where('tracking_number', $trackingNumber)->firstOrFail();
        $shipment->delete();
        
        return redirect()->route('admin.shipments')
            ->with('success', 'Shipment deleted successfully.');
    }

    /**
     * Add a tracking update to a shipment.
     */
    public function addTrackingUpdate(TrackingUpdateRequest $request, $trackingNumber)
    {
        $shipment = Shipment::where('tracking_number', $trackingNumber)->firstOrFail();
        $shipment->addTrackingUpdate($request->status, $request->notes);
        
        return redirect()->route('admin.shipments.show', $shipment->tracking_number)
            ->with('success', 'Tracking update added successfully.');
    }

    /**
     * Display all contact form submissions.
     */
    public function contacts()
    {
        $contacts = Contact::latest()->paginate(15);
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Display a specific contact form submission.
     */
    public function showContact($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.contacts.show', compact('contact'));
    }
}