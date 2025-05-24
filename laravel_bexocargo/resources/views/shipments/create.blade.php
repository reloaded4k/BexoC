@extends('layouts.app')

@section('title', 'Book Shipment')

@section('content')
<div class="booking-page">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4">Book Your Shipment</h2>
            <p class="text-center mb-5">Fill out the form below to book your shipment with Bexo Cargo</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('shipments.store') }}" method="POST" id="shipmentForm">
                @csrf
                
                <!-- Form Steps Navigation -->
                <div class="booking-steps mb-4">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="booking-step active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-title">Sender Details</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="booking-step" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-title">Recipient Details</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="booking-step" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-title">Cargo Details</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Sender Information -->
                <div class="form-step active" id="step1">
                    <h4 class="mb-3 border-bottom pb-2">Sender Information</h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sender_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sender_name') is-invalid @enderror" id="sender_name" name="sender_name" value="{{ old('sender_name') }}" required>
                            @error('sender_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="sender_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('sender_email') is-invalid @enderror" id="sender_email" name="sender_email" value="{{ old('sender_email') }}" required>
                            @error('sender_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sender_contact" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sender_contact') is-invalid @enderror" id="sender_contact" name="sender_contact" value="{{ old('sender_contact') }}" required>
                            @error('sender_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="sender_address" class="form-label">Full Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('sender_address') is-invalid @enderror" id="sender_address" name="sender_address" rows="3" required>{{ old('sender_address') }}</textarea>
                            @error('sender_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-primary next-step" data-step="1">Next: Recipient Details <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>
                </div>

                <!-- Step 2: Recipient Information -->
                <div class="form-step" id="step2">
                    <h4 class="mb-3 border-bottom pb-2">Recipient Information</h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="recipient_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}" required>
                            @error('recipient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="recipient_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('recipient_email') is-invalid @enderror" id="recipient_email" name="recipient_email" value="{{ old('recipient_email') }}" required>
                            @error('recipient_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="recipient_contact" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('recipient_contact') is-invalid @enderror" id="recipient_contact" name="recipient_contact" value="{{ old('recipient_contact') }}" required>
                            @error('recipient_contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="recipient_address" class="form-label">Full Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('recipient_address') is-invalid @enderror" id="recipient_address" name="recipient_address" rows="3" required>{{ old('recipient_address') }}</textarea>
                            @error('recipient_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary prev-step" data-step="2"><i class="fas fa-arrow-left me-1"></i> Back: Sender Details</button>
                        <button type="button" class="btn btn-primary next-step" data-step="2">Next: Cargo Details <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>
                </div>

                <!-- Step 3: Cargo Information -->
                <div class="form-step" id="step3">
                    <h4 class="mb-3 border-bottom pb-2">Cargo Information</h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cargo_name" class="form-label">Cargo Name/Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('cargo_name') is-invalid @enderror" id="cargo_name" name="cargo_name" value="{{ old('cargo_name') }}" required>
                            @error('cargo_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="cargo_description" class="form-label">Cargo Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('cargo_description') is-invalid @enderror" id="cargo_description" name="cargo_description" rows="3" placeholder="Please provide details about your cargo (contents, quantity, etc)" required>{{ old('cargo_description') }}</textarea>
                            @error('cargo_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <p class="mb-2">Dimensions (optional)</p>
                        </div>
                        <div class="col-md-3">
                            <label for="cargo_length" class="form-label">Length (cm)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('cargo_length') is-invalid @enderror" id="cargo_length" name="cargo_length" value="{{ old('cargo_length') }}">
                            @error('cargo_length')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cargo_width" class="form-label">Width (cm)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('cargo_width') is-invalid @enderror" id="cargo_width" name="cargo_width" value="{{ old('cargo_width') }}">
                            @error('cargo_width')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cargo_height" class="form-label">Height (cm)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('cargo_height') is-invalid @enderror" id="cargo_height" name="cargo_height" value="{{ old('cargo_height') }}">
                            @error('cargo_height')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cargo_weight" class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('cargo_weight') is-invalid @enderror" id="cargo_weight" name="cargo_weight" value="{{ old('cargo_weight') }}">
                            @error('cargo_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i> By submitting this form, you agree to our <a href="{{ route('shipping-terms') }}" target="_blank">Shipping Terms</a>.
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary prev-step" data-step="3"><i class="fas fa-arrow-left me-1"></i> Back: Recipient Details</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check-circle me-1"></i> Complete Booking</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .booking-steps {
        margin-bottom: 30px;
    }
    
    .booking-step {
        position: relative;
        padding: 10px;
        opacity: 0.5;
        transition: all 0.3s ease;
    }
    
    .booking-step.active {
        opacity: 1;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto 10px;
        transition: all 0.3s ease;
    }
    
    .booking-step.active .step-number {
        background-color: var(--bs-primary);
        color: white;
    }
    
    .step-title {
        font-weight: 500;
    }
    
    .form-step {
        display: none;
    }
    
    .form-step.active {
        display: block;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form step navigation
        const nextButtons = document.querySelectorAll('.next-step');
        const prevButtons = document.querySelectorAll('.prev-step');
        const steps = document.querySelectorAll('.booking-step');
        
        nextButtons.forEach(button => {
            button.addEventListener('click', function() {
                const currentStep = parseInt(this.getAttribute('data-step'));
                const nextStep = currentStep + 1;
                
                // Validate current step
                if (validateStep(currentStep)) {
                    // Hide current step and show next step
                    document.getElementById('step' + currentStep).classList.remove('active');
                    document.getElementById('step' + nextStep).classList.add('active');
                    
                    // Update step indicators
                    steps.forEach(step => step.classList.remove('active'));
                    document.querySelector(`.booking-step[data-step="${nextStep}"]`).classList.add('active');
                    
                    // Scroll to top of form
                    document.getElementById('shipmentForm').scrollIntoView({behavior: 'smooth'});
                }
            });
        });
        
        prevButtons.forEach(button => {
            button.addEventListener('click', function() {
                const currentStep = parseInt(this.getAttribute('data-step'));
                const prevStep = currentStep - 1;
                
                // Hide current step and show previous step
                document.getElementById('step' + currentStep).classList.remove('active');
                document.getElementById('step' + prevStep).classList.add('active');
                
                // Update step indicators
                steps.forEach(step => step.classList.remove('active'));
                document.querySelector(`.booking-step[data-step="${prevStep}"]`).classList.add('active');
                
                // Scroll to top of form
                document.getElementById('shipmentForm').scrollIntoView({behavior: 'smooth'});
            });
        });
        
        // Form validation
        function validateStep(step) {
            let isValid = true;
            const stepElement = document.getElementById('step' + step);
            
            // Check all required fields in the current step
            const requiredFields = stepElement.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Add specific validation for email fields
            const emailFields = stepElement.querySelectorAll('input[type="email"]');
            emailFields.forEach(field => {
                if (field.value && !validateEmail(field.value)) {
                    field.classList.add('is-invalid');
                    isValid = false;
                }
            });
            
            if (!isValid) {
                // Scroll to the first invalid field
                const firstInvalidField = stepElement.querySelector('.is-invalid');
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({behavior: 'smooth', block: 'center'});
                    firstInvalidField.focus();
                }
            }
            
            return isValid;
        }
        
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        // Add form validation before submission
        document.getElementById('shipmentForm').addEventListener('submit', function(e) {
            if (!validateStep(3)) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush