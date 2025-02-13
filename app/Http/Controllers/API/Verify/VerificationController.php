<?php

namespace App\Http\Controllers\API\Verify;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify($id,$hash)
    {
        if (! request()->hasValidSignature()) {
            return redirect('/')->with('error', 'Link xác thực không hợp lệ hoặc đã hết hạn.');
        }
    
        $user = User::findOrFail($id);
    
        if (sha1($user->email) !== $hash) {
            return redirect('/')->with('error', 'Link xác thực không hợp lệ.');
        }
    
        // Mark email as verified
        $user->markEmailAsVerified();
    
        return redirect('/home')->with('verified', true);
    }
}
