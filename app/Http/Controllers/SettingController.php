<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function edit()
    {
        return view('settings.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'notification' => 'boolean',
            'theme' => 'string|in:light,dark',
        ]);

        Auth::user()->update([
            'notification' => $request->notification,
            'theme' => $request->theme,
        ]);

        return redirect()->route('settings')->with('success', 'Settings updated successfully!');
    }
}
