<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribes,email'
        ], [
            'email.required' => 'Внесете емаил адреса.',
            'email.email' => 'Внесете валидан емаил адреса.',
            'email.unique' => 'Веќе сте зачленети.'
        ]);

        Subscribe::create([
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Се зачленивте. Од сега прв/а ќе добивате известување за нашите промоции, попусти и нови колекции.'
        ], 200);
    }
}
