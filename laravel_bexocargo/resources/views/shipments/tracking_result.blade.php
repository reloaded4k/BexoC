@extends('layouts.app')

@section('title', 'Tracking Results')

@section('content')
<div class="tracking-result-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="tracking-header mb-4">
                    <h2 class="text-center">Shipment Tracking</h2>
                    <div class="text-center">
                        <span class="badge bg-primary p-2 fs-6">Tracking Number: {{ $shipment->tracking_number }}</span>
                    </div>
                </div>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i> Shipment Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="status-badge p-3 text-center rounded">
                                    <h6 class="text-muted mb-1">Current Status</h6>
                                    <span class="badge bg-{{ 
                                        $shipment->current_status === 'Delivered' ? 'success' : 
                                        ($shipment->current_status === 'In Transit' ? 'info' : 
                                        ($shipment->current_status === 'Cancelled' ? 'danger' : 'warning')) 
                                    }} p-2 fs-6">{{ $shipment->current_status }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="status-badge p-3 text-center rounded">
                                    <h6 class="text-muted mb-1">Shipment Date</h6>
                                    <span class="fw-bold">{{ $shipment->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="status-badge p-3 text-center rounded">
                                    <h6 class="text-muted mb-1">Cargo Type</h6>
                                    <span class="fw-bold">{{ $shipment->cargo_name }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row mt-3">
                            <div class="col-md-6 mb-4">
                                <div class="address-box p-3 rounded border">
                                    <h6><i class="fas fa-user-edit me-2"></i> Sender</h6>
                                    <p class="mb-1"><strong>{{ $shipment->sender_name }}</strong></p>
                                    <p class="mb-1">{{ $shipment->sender_address }}</p>
                                    <p class="mb-1">
                                        <i class="fas fa-phone me-1 text-muted"></i> {{ $shipment->sender_contact }}<br>
                                        <i class="fas fa-envelope me-1 text-muted"></i> {{ $shipment->sender_email }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="address-box p-3 rounded border">
                                    <h6><i class="fas fa-user-check me-2"></i> Recipient</h6>
                                    <p class="mb-1"><strong>{{ $shipment->recipient_name }}</strong></p>
                                    <p class="mb-1">{{ $shipment->recipient_address }}</p>
                                    <p class="mb-1">
                                        <i class="fas fa-phone me-1 text-muted"></i> {{ $shipment->recipient_contact }}<br>
                                        <i class="fas fa-envelope me-1 text-muted"></i> {{ $shipment->recipient_email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="cargo-info p-3 rounded border">
                                    <h6><i class="fas fa-box me-2"></i> Cargo Details</h6>
                                    <p class="mb-1">{{ $shipment->cargo_description }}</p>
                                    @if($shipment->cargo_length && $shipment->cargo_width && $shipment->cargo_height)
                                        <p class="mb-0">
                                            <span class="text-muted">Dimensions:</span> 
                                            {{ $shipment->cargo_length }} x {{ $shipment->cargo_width }} x {{ $shipment->cargo_height }} cm
                                        </p>
                                    @endif
                                    @if($shipment->cargo_weight)
                                        <p class="mb-0">
                                            <span class="text-muted">Weight:</span> 
                                            {{ $shipment->cargo_weight }} kg
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shipping-fast me-2"></i> Tracking History
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="shipment-timeline">
                            @forelse($shipment->trackingUpdates()->orderBy('created_at', 'desc')->get() as $update)
                                <div class="timeline-item">
                                    <div class="timeline-point bg-{{ 
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
                                    <div class="timeline-content">
                                        <div class="timeline-time">{{ $update->created_at->format('M d, Y - H:i') }}</div>
                                        <h6 class="timeline-title">{{ $update->status }}</h6>
                                        <div class="timeline-text">{{ $update->notes }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-muted">No tracking updates available yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="tracking-actions text-center mb-5">
                    <a href="{{ route('shipments.track-form') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-1"></i> Track Another Shipment
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-envelope me-1"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tracking-result-page {
        padding: 30px 0;
    }
    
    .status-badge {
        background-color: #f8f9fa;
    }
    
    .address-box, .cargo-info {
        background-color: #f8f9fa;
    }
    
    /* Timeline styling */
    .shipment-timeline {
        position: relative;
        margin: 20px 0;
        padding-left: 50px;
    }
    
    .shipment-timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 20px;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-point {
        position: absolute;
        left: -50px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
        color: white;
    }
    
    .timeline-content {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    
    .timeline-time {
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 5px;
    }
    
    .timeline-title {
        margin-bottom: 10px;
        color: #495057;
    }
    
    .timeline-text {
        color: #495057;
    }
</style>
@endpush