<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use Illuminate\Http\Request;

class MailingListController extends Controller
{
    public function subscribe(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:mailing_list,email',
        ]);

        MailingList::create($validatedData);

        return redirect()->back()->with('status', 'Successfully subscribed to the mailing list!');
    }
}
