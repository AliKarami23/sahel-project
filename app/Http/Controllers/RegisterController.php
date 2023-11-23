<?php

namespace App\Http\Controllers;

use App\Events\CleanUpVerificationCodes;
use App\Jobs\CleanUpVerificationCodesJob;
use App\Models\PhoneNumber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Sms\app\Http\Controllers\SmsController;
use Illuminate\Validation\ValidationException;


class RegisterController extends Controller
{
    public function GetNumber(Request $request)
    {
        $Phone_Number = intval($request->Phone_Number);

        $Verification_Code = mt_rand(10000, 99999);
        $tmp = new SmsController();
        $tmp->VerificationCode($Phone_Number, $Verification_Code);
        PhoneNumber::create([
            'number' => $Phone_Number,
            'verification_code' => $Verification_Code,
        ]);

        event(new CleanUpVerificationCodes());

        CleanUpVerificationCodesJob::dispatch()->delay(now()->addMinutes(3));

        return response()->json(['message' => 'Verification code has been sent.']);

    }


    public function GetCodeSent(Request $request)
    {
        $Phone_Number = $request->Phone_Number;
        $verificationCode = $request->verification_code;

        $phoneNumber = PhoneNumber::where('number', $Phone_Number)
            ->where('verification_code', $verificationCode)
            ->first();

        if (!$phoneNumber || $phoneNumber->created_at < now()->subMinutes(3)) {
            return response()->json(['message' => 'Invalid or expired verification code.'], 401);
        }

        $User = User::updateOrcreate([
            'Phone_Number' => $Phone_Number,
        ]);
        $User->assignRole('Customer');
        $token = $User->createToken('UserToken')->plainTextToken;
        return response()->json(['token' => $token, 'message' => 'Token created successfully.']);
    }

    public function GetInformation(Request $request)
    {
        $full_name = $request->Full_Name;
        $email = $request->Email;

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->update([
            'Full_Name' => $full_name,
            'Email' => $email,
        ]);

        return response()->json([
            'message' => 'User information updated successfully.',
            'user' => $user
        ]);
    }


    public function AdminLogin(Request $request)
    {

        $user = User::where('Email', $request->Email)->first();

        if (!$user || !Hash::check($request->Password, $user->Password)) {
            throw ValidationException::withMessages([
                'Email' => ['Invalid credentials']
            ]);
        }

        $token = $user->createToken('UserToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);

    }

    public function Logout(Request $request)
    {

        $request->user()->tokens()->delete();
        return response()->json([
            'Logout' => 'Goodbye'
        ]);
    }


}
