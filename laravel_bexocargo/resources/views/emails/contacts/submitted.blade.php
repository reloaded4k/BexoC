<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
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
        .info-section {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .info-section h3 {
            margin-top: 0;
            color: #007bff;
        }
        .message-box {
            background-color: #f0f0f0;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 15px 0;
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
            <h1>New Contact Form Submission</h1>
        </div>
        
        <div class="content">
            <p>A new contact form submission has been received from the Bexo Cargo website.</p>
            
            <div class="info-section">
                <h3>Contact Information</h3>
                <p><strong>Name:</strong> {{ $contact->name }}</p>
                <p><strong>Email:</strong> {{ $contact->email }}</p>
                <p><strong>Contact Number:</strong> {{ $contact->contact_number }}</p>
                <p><strong>Address:</strong> {{ $contact->address }}</p>
                <p><strong>Submission Date:</strong> {{ $contact->created_at->format('M d, Y H:i') }}</p>
            </div>
            
            <div class="info-section">
                <h3>Message</h3>
                <div class="message-box">
                    {{ $contact->message }}
                </div>
            </div>
            
            <p>Please respond to this inquiry as soon as possible.</p>
            
            <center>
                <a href="mailto:{{ $contact->email }}" class="button">Reply to {{ $contact->name }}</a>
            </center>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Bexo Cargo. All rights reserved.</p>
            <p>This is an automated notification from the Bexo Cargo website.</p>
            <p>Admin Portal: <a href="{{ route('admin.dashboard') }}">{{ route('admin.dashboard') }}</a></p>
        </div>
    </div>
</body>
</html>