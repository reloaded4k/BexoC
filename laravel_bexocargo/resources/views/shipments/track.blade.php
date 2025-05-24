@extends('layouts.app')

@section('title', 'Track Shipment')

@section('content')
<div class="tracking-page">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <h2 class="text-center mb-4">Track Your Shipment</h2>
            <p class="text-center mb-5">Enter your tracking number to get real-time updates on your shipment</p>
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('shipments.track') }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" name="tracking_number" class="form-control form-control-lg @error('tracking_number') is-invalid @enderror" placeholder="Enter tracking number (e.g., BX12345678)" value="{{ old('tracking_number') }}" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search me-2"></i> Track
                            </button>
                        </div>
                        
                        @error('tracking_number')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </form>
                    
                    <div class="tracking-examples mt-4">
                        <p class="text-muted"><small><i class="fas fa-info-circle me-1"></i> Your tracking number is a 10-digit code starting with "BX" found in your booking confirmation email.</small></p>
                    </div>
                </div>
            </div>
            
            <div class="tracking-features mt-5">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-globe"></i>
                            </div>
                            <h5>Global Tracking</h5>
                            <p class="text-muted">Track shipments anywhere in the world with our real-time tracking system</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-history"></i>
                            </div>
                            <h5>Complete History</h5>
                            <p class="text-muted">View the complete timeline of your shipment's journey from pickup to delivery</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h5>Status Updates</h5>
                            <p class="text-muted">Receive detailed status updates at every stage of the shipping process</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tracking-help mt-5">
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fas fa-question-circle me-2"></i> Need Help?</h5>
                        <p>If you're having trouble tracking your shipment or need more information, please contact our customer service team:</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-envelope me-2"></i> Email: <a href="mailto:support@bexocargo.com">support@bexocargo.com</a></li>
                            <li><i class="fas fa-phone me-2"></i> Phone: +1-234-567-8900</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tracking-page {
        padding: 30px 0;
    }
    
    .feature-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background-color: #f8f9fa;
        border-radius: 50%;
    }
    
    .feature-icon i {
        font-size: 24px;
        color: var(--bs-primary);
    }
</style>
@endpush