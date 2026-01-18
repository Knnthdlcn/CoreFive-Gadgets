<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contactus');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', "regex:/^[A-Za-z]+(?:[-'][A-Za-z]+)*(?:\\s+[A-Za-z]+(?:[-'][A-Za-z]+)*)*$/"],
            'email' => 'required|email',
            'contact' => ['required', 'regex:/^09\d{9}$/'],
            'message' => 'required|string|min:10',
        ], [
            'name.regex' => 'Full name may only contain letters, spaces, hyphens, and apostrophes.',
        ]);

        Contact::create($validated);

        return response()->json([
            'message' => 'Message sent successfully! We\'ll get back to you soon.',
        ], 201);
    }
}
