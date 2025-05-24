<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
        }
        .tracking-number {
            font-size: 18px;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 10px;
            text-align: center;
            margin: 15px 0;
            border: 1px dashed #ccc;
        }
        .info-section {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .info-section h3 {
            margin-top: 0;
            color: #007bff;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Shipment Booking Confirmation</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $shipment->sender_name }},</p>
            
            <p>Thank you for choosing Bexo Cargo for your shipping needs. Your shipment has been successfully booked with us.</p>
            
            <div class="tracking-number">
                Tracking Number: {{ $shipment->tracking_number }}
            </div>
            
            <p>Please keep this tracking number for your reference. You can track your shipment status anytime by visiting our <a href="{{ route('shipments.track-form') }}">tracking page</a>.</p>
            
            <div class="info-section">
                <h3>Shipment Details</h3>
                <p><strong>Booking Date:</strong> {{ $shipment->created_at->format('M d, Y') }}</p>
                <p><strong>Current Status:</strong> {{ $shipment->current_status }}</p>
                <p><strong>Cargo:</strong> {{ $shipment->cargo_name }}</p>
            </div>
            
            <div class="info-section">
                <h3>Sender Information</h3>
                <p><strong>Name:</strong> {{ $shipment->sender_name }}</p>
                <p><strong>Email:</strong> {{ $shipment->sender_email }}</p>
                <p><strong>Contact:</strong> {{ $shipment->sender_contact }}</p>
                <p><strong>Address:</strong> {{ $shipment->sender_address }}</p>
            </div>
            
            <div class="info-section">
                <h3>Recipient Information</h3>
                <p><strong>Name:</strong> {{ $shipment->recipient_name }}</p>
                <p><strong>Email:</strong> {{ $shipment->recipient_email }}</p>
                <p><strong>Contact:</strong> {{ $shipment->recipient_contact }}</p>
                <p><strong>Address:</strong> {{ $shipment->recipient_address }}</p>
            </div>
            
            <p>Our team will start processing your shipment shortly. You will receive updates as your shipment progresses.</p>
            
            <p>If you have any questions or need assistance, please don't hesitate to contact our customer service team.</p>
            
            <center>
                <a href="{{ route('shipments.tracking-result', $shipment->tracking_number) }}" class="button">Track Your Shipment</a>
            </center>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Bexo Cargo. All rights reserved.</p>
            <p>123 Shipping Lane, Logistics Park, Nairobi, Kenya</p>
            <p>Email: support@bexocargo.com | Phone: +254 712 345 678</p>
        </div>
    </div>
</body>
</html>