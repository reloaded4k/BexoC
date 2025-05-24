@extends('layouts.admin')

@section('title', 'Manage Shipments')
@section('page-title', 'Shipments Management')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">All Shipments</h6>
        <a href="{{ route('shipments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Add New Shipment
        </a>
    </div>
    <div class="card-body">
        <!-- Shipment Filter -->
        <div class="mb-4">
            <form action="{{ route('admin.shipments') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search tracking number or name" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Booking Received" {{ request('status') == 'Booking Received' ? 'selected' : '' }}>Booking Received</option>
                        <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Dispatched" {{ request('status') == 'Dispatched' ? 'selected' : '' }}>Dispatched</option>
                        <option value="In Transit" {{ request('status') == 'In Transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="Out For Delivery" {{ request('status') == 'Out For Delivery' ? 'selected' : '' }}>Out For Delivery</option>
                        <option value="Custom Clearance Export" {{ request('status') == 'Custom Clearance Export' ? 'selected' : '' }}>Custom Clearance Export</option>
                        <option value="Custom Clearance Import" {{ request('status') == 'Custom Clearance Import' ? 'selected' : '' }}>Custom Clearance Import</option>
                        <option value="Shipment On Hold" {{ request('status') == 'Shipment On Hold' ? 'selected' : '' }}>Shipment On Hold</option>
                        <option value="Shipment Pending Release" {{ request('status') == 'Shipment Pending Release' ? 'selected' : '' }}>Shipment Pending Release</option>
                        <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.shipments') }}" class="btn btn-secondary ms-1">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Shipments Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tracking #</th>
                        <th>Sender</th>
                        <th>Recipient</th>
                        <th>Cargo</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shipments as $shipment)
                    <tr>
                        <td>{{ $shipment->tracking_number }}</td>
                        <td>
                            <strong>{{ $shipment->sender_name }}</strong><br>
                            <small>{{ $shipment->sender_email }}</small>
                        </td>
                        <td>
                            <strong>{{ $shipment->recipient_name }}</strong><br>
                            <small>{{ $shipment->recipient_email }}</small>
                        </td>
                        <td>{{ $shipment->cargo_name }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $shipment->current_status === 'Delivered' ? 'success' : 
                                ($shipment->current_status === 'In Transit' ? 'info' : 
                                ($shipment->current_status === 'Cancelled' ? 'danger' : 'warning')) 
                            }}">
                                {{ $shipment->current_status }}
                            </span>
                        </td>
                        <td>{{ $shipment->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.shipments.show', $shipment->tracking_number) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.shipments.edit', $shipment->tracking_number) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $shipment->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $shipment->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the shipment with tracking number <strong>{{ $shipment->tracking_number }}</strong>?
                                            <p class="text-danger mt-2">This action cannot be undone.</p>
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No shipments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $shipments->links() }}
        </div>
    </div>
</div>
@endsection