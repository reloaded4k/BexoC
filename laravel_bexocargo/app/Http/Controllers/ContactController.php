<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactFormSubmitted;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display the contact form.
     */
    public function create()
    {
        return view('contact');
    }

    /**
     * Store a new contact form submission.
     */
    public function store(ContactRequest $request)
    {
        // Create the contact with validated data
        $contact = Contact::create($request->validated());
        
        // Send notification email
        try {
            Mail::to(config('mail.admin_email', 'support@bexocargo.com'))
                ->send(new ContactFormSubmitted($contact));
        } catch (\Exception $e) {
            // Log the error but continue
            \Log::error('Failed to send contact email: ' . $e->getMessage());
        }
        
        // Redirect back with success message
        return redirect()->back()
            ->with('success', 'Your message has been sent successfully. We will contact you shortly.');
    }
}