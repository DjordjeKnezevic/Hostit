<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required',
        ]);

        ContactMessage::create($request->only('name', 'email', 'message'));

        return redirect()->back()->with('status', 'Your message has been sent successfully!');
    }
}
