@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="home-page">
    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-5 mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold">Global Shipping & Logistics Solutions</h1>
                    <p class="lead">Efficient, reliable shipping and logistics services for African markets and beyond.</p>
                    <div class="mt-4">
                        <a href="{{ route('shipments.create') }}" class="btn btn-light btn-lg me-2">Book Shipment</a>
                        <a href="{{ route('shipments.track-form') }}" class="btn btn-outline-light btn-lg">Track Shipment</a>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('images/hero-image.png') }}" alt="Shipping and Logistics" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Our Services</h2>
                <p class="section-subtitle">Comprehensive shipping and logistics solutions for all your needs</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/services/air-courier.png') }}" alt="Air Courier" class="service-icon mb-3">
                            <h3 class="card-title">Air Courier</h3>
                            <p class="card-text">Fast and reliable air courier services for urgent shipments across the globe.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/services/door-to-door.png') }}" alt="Door to Door" class="service-icon mb-3">
                            <h3 class="card-title">Door to Door</h3>
                            <p class="card-text">Convenient door-to-door delivery services for hassle-free shipping experience.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 service-card">
                        <div class="card-body text-center">
                            <img src="{{ asset('images/services/bulk-cargo.png') }}" alt="Bulk Cargo" class="service-icon mb-3">
                            <h3 class="card-title">Bulk Cargo</h3>
                            <p class="card-text">Efficient handling and transport of bulk cargo for commercial shipments.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">Simple steps to book and track your shipments</p>
            </div>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="step-card text-center">
                        <div class="step-number">1</div>
                        <h4>Book Shipment</h4>
                        <p>Fill out our simple booking form with shipment details</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="step-card text-center">
                        <div class="step-number">2</div>
                        <h4>Confirmation</h4>
                        <p>Receive booking confirmation and tracking number</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="step-card text-center">
                        <div class="step-number">3</div>
                        <h4>Pickup & Transit</h4>
                        <p>We pick up and transport your cargo safely</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="step-card text-center">
                        <div class="step-number">4</div>
                        <h4>Delivery</h4>
                        <p>Your shipment is delivered to the destination</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('shipments.create') }}" class="btn btn-primary btn-lg">Book Your Shipment Now</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">What Our Customers Say</h2>
                <p class="section-subtitle">We pride ourselves on excellent customer service</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 testimonial-card">
                        <div class="card-body">
                            <div class="testimonial-image">
                                <img src="{{ asset('images/testimonials/kwame-boateng.png') }}" alt="Kwame Boateng" class="rounded-circle">
                            </div>
                            <div class="testimonial-content">
                                <p class="testimonial-text">"Bexo Cargo provided exceptional service for our shipment from Ghana to the UK. Everything arrived on time and in perfect condition."</p>
                                <h5 class="testimonial-name">Kwame Boateng</h5>
                                <p class="testimonial-position">Business Owner, Accra</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 testimonial-card">
                        <div class="card-body">
                            <div class="testimonial-image">
                                <img src="{{ asset('images/testimonials/amandia-sam.png') }}" alt="Amandia Sam" class="rounded-circle">
                            </div>
                            <div class="testimonial-content">
                                <p class="testimonial-text">"I've been using Bexo Cargo for shipping to Nigeria for years. Their tracking system keeps me updated at every step of the journey."</p>
                                <h5 class="testimonial-name">Amandia Sam</h5>
                                <p class="testimonial-position">Import Manager, Lagos</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 testimonial-card">
                        <div class="card-body">
                            <div class="testimonial-image">
                                <img src="{{ asset('images/testimonials/samuel-ochieng.png') }}" alt="Samuel Ochieng" class="rounded-circle">
                            </div>
                            <div class="testimonial-content">
                                <p class="testimonial-text">"The customer service team went above and beyond to help with my urgent shipment to Kenya. I cannot recommend them enough!"</p>
                                <h5 class="testimonial-name">Samuel Ochieng</h5>
                                <p class="testimonial-position">Project Manager, Nairobi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2>Ready to Ship with Bexo Cargo?</h2>
                    <p class="lead">Experience hassle-free shipping and logistics services tailored to your needs.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg">Contact Us Today</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    .hero-section {
        border-radius: 0 0 15px 15px;
    }
    
    .service-icon {
        width: 80px;
        height: 80px;
        margin-bottom: 20px;
    }
    
    .service-card {
        transition: all 0.3s ease;
        border-radius: 10px;
    }
    
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .step-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        background-color: var(--bs-primary);
        color: white;
        border-radius: 50%;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .testimonial-image img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        margin-bottom: 15px;
    }
    
    .testimonial-card {
        border-radius: 10px;
    }
</style>
@endpush