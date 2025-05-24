@extends('layouts.admin')

@section('title', 'View Shipment')
@section('page-title', 'Shipment Details')

@section('content')
<div class="row">
    <!-- Shipment Details -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Shipment #{{ $shipment->tracking_number }}
                </h6>
                <div>
                    <a href="{{ route('admin.shipments.edit', $shipment->tracking_number) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteShipmentModal">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="badge bg-{{ 
                            $shipment->current_status === 'Delivered' ? 'success' : 
                            ($shipment->current_status === 'In Transit' ? 'info' : 
                            ($shipment->current_status === 'Cancelled' ? 'danger' : 'warning')) 
                        }} mb-2 p-2">
                            Current Status: {{ $shipment->current_status }}
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <strong>Created:</strong> {{ $shipment->created_at->format('M d, Y H:i') }}
                    </div>
                </div>
                
                <div class="row mb-4">
                    <!-- Sender Information -->
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 text-primary">Sender Information</h5>
                        <div class="mb-3">
                            <strong>Name:</strong> {{ $shipment->sender_name }}
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong> {{ $shipment->sender_email }}
                        </div>
                        <div class="mb-3">
                            <strong>Contact:</strong> {{ $shipment->sender_contact }}
                        </div>
                        <div class="mb-3">
                            <strong>Address:</strong><br>
                            {{ $shipment->sender_address }}
                        </div>
                    </div>
                    
                    <!-- Recipient Information -->
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2 text-primary">Recipient Information</h5>
                        <div class="mb-3">
                            <strong>Name:</strong> {{ $shipment->recipient_name }}
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong> {{ $shipment->recipient_email }}
                        </div>
                        <div class="mb-3">
                            <strong>Contact:</strong> {{ $shipment->recipient_contact }}
                        </div>
                        <div class="mb-3">
                            <strong>Address:</strong><br>
                            {{ $shipment->recipient_address }}
                        </div>
                    </div>
                </div>
                
                <!-- Cargo Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="border-bottom pb-2 text-primary">Cargo Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Name:</strong> {{ $shipment->cargo_name }}
                                </div>
                                <div class="mb-3">
                                    <strong>Description:</strong><br>
                                    {{ $shipment->cargo_description }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Dimensions:</strong><br>
                                    @if($shipment->cargo_length && $shipment->cargo_width && $shipment->cargo_height)
                                        {{ $shipment->cargo_length }} x {{ $shipment->cargo_width }} x {{ $shipment->cargo_height }} cm
                                    @else
                                        Not specified
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <strong>Weight:</strong>
                                    {{ $shipment->cargo_weight ? $shipment->cargo_weight . ' kg' : 'Not specified' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Tracking Update -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Add Tracking Update</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.shipments.tracking.add', $shipment->tracking_number) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">Select Status</option>
                            <option value="Booking Received">Booking Received</option>
                            <option value="Processing">Processing</option>
                            <option value="Dispatched">Dispatched</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Out For Delivery">Out For Delivery</option>
                            <option value="Custom Clearance Export">Custom Clearance Export</option>
                            <option value="Custom Clearance Import">Custom Clearance Import</option>
                            <option value="Shipment On Hold">Shipment On Hold</option>
                            <option value="Shipment Pending Release">Shipment Pending Release</option>
                            <option value="Shipment On Hold (Docs Request)">Shipment On Hold (Docs Request)</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="form-control" required></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Add Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.shipments') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Shipments
                    </a>
                    <a href="{{ route('shipments.tracking-result', $shipment->tracking_number) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-eye me-1"></i> View Tracking Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tracking History -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tracking History</h6>
    </div>
    <div class="card-body">
        <div class="tracking-timeline">
            @forelse($shipment->trackingUpdates()->latest()->get() as $update)
                <div class="tracking-item">
                    <div class="tracking-icon bg-{{ 
                        $update->status === 'Delivered' ? 'success' : 
                        ($update->status === 'In Transit' ? 'info' : 
                        ($update->status === 'Cancelled' ? 'danger' : 'warning')) 
                    }}">
                        <i class="fas fa-{{ 
                            $update->status === 'Delivered' ? 'check' : 
                            ($update->status === 'In Transit' ? 'shipping-fast' : 
                            ($update->status === 'Cancelled' ? 'times' : 'clock')) 
                        }}"></i>
                    </div>
                    <div class="tracking-content">
                        <h6 class="tracking-title">{{ $update->status }}</h6>
                        <span class="tracking-date">{{ $update->created_at->format('M d, Y H:i') }}</span>
                        <p class="tracking-text">{{ $update->notes }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-3">
                    <p class="text-muted">No tracking updates found</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Delete Shipment Modal -->
<div class="modal fade" id="deleteShipmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this shipment?
                <p class="text-danger mt-2">This action cannot be undone and will delete all associated tracking updates.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.shipments.destroy', $shipment->tracking_number) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Shipment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tracking-timeline {
        position: relative;
        padding-left: 50px;
    }
    
    .tracking-timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 20px;
        width: 2px;
        background-color: #e0e0e0;
    }
    
    .tracking-item {
        position: relative;
        margin-bottom: 25px;
    }
    
    .tracking-icon {
        position: absolute;
        left: -50px;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        text-align: center;
        color: #fff;
        line-height: 40px;
    }
    
    .tracking-content {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    
    .tracking-title {
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .tracking-date {
        font-size: 0.8rem;
        color: #6c757d;
        display: block;
        margin-bottom: 10px;
    }
    
    .tracking-text {
        margin-bottom: 0;
    }
</style>
@endpush