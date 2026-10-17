<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController
{
    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('website')) {
            return redirect()->to(route('home').'#contact')->with('contact_success', 'Your message has been received.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:254'],
            'subject' => ['required', 'string', 'min:3', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        ContactMessage::create($data);

        return redirect()->to(route('home').'#contact')->with('contact_success', 'Your message has been received.');
    }
}
