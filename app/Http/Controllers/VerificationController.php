<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;

class VerificationController extends Controller
{
    public function verify(Request  $request)
    {
        
        $user = User::findOrFail($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        if ($user->role == 'admin') {
            return redirect()->route('admin.login')->with([
                'status' => 'success',
                'message' => 'Email verified successfully!.',
            ]);
        
        }elseif ($user->role == 'vendor') {
            return redirect()->route('vendor.login')->with([
                'status' => 'success',
                'message' => 'Email verified successfully!.',
            ]);
        
        }else {
            return redirect()->route('transporter.login')->with([
                'status' => 'success',
                'message' => 'Email verified successfully!.',
            ]);
        
        }

        // return redirect('/login')->with([
        //     'status' => 'success',
        //     'message' => 'Email verified successfully!.',
        // ]);
    
    }
}