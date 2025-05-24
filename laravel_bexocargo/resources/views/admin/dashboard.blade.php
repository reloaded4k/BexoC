@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard">
    <!-- Dashboard Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Shipments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalShipments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shipping-fast fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pending Shipments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingShipments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                In Transit</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inTransitShipments }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Delivered</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $deliveredShipments ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Shipments & Actions -->
    <div class="row">
        <!-- Recent Shipments -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Shipments</h6>
                    <a href="{{ route('admin.shipments') }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tracking #</th>
                                    <th>Sender</th>
                                    <th>Recipient</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentShipments as $shipment)
                                <tr>
                                    <td>{{ $shipment->tracking_number }}</td>
                                    <td>{{ $shipment->sender_name }}</td>
                                    <td>{{ $shipment->recipient_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $shipment->current_status === 'Delivered' ? 'success' : ($shipment->current_status === 'In Transit' ? 'info' : 'warning') }}">
                                            {{ $shipment->current_status }}
                                        </span>
                                    </td>
                                    <td>{{ $shipment->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.shipments.show', $shipment->tracking_number) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No shipments found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('shipments.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-plus-circle me-2"></i> New Shipment
                        </a>
                        <a href="{{ route('admin.shipments') }}" class="btn btn-info btn-block">
                            <i class="fas fa-list me-2"></i> All Shipments
                        </a>
                        <a href="{{ route('admin.contacts') }}" class="btn btn-success btn-block">
                            <i class="fas fa-envelope me-2"></i> Contact Messages
                        </a>
                        <hr>
                        <a href="{{ route('shipments.track-form') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-search me-2"></i> Track Shipment
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">System Info</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Laravel Version:</strong> {{ app()->version() }}
                    </div>
                    <div class="mb-2">
                        <strong>PHP Version:</strong> {{ phpversion() }}
                    </div>
                    <div class="mb-2">
                        <strong>Environment:</strong> {{ app()->environment() }}
                    </div>
                    <div>
                        <strong>Server Time:</strong> {{ now()->format('Y-m-d H:i:s') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }
    .border-left-success {
        border-left: 4px solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 4px solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e !important;
    }
</style>
@endpush