<?php

namespace App\Http\Controllers;

use App\Jobs\CleanUpVerificationCodesJob;
use App\Mail\VerificationCodeMail;
use App\Models\Register;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Sms\app\Http\Controllers\SmsController;
use Illuminate\Validation\ValidationException;


class RegisterController extends Controller
{
    public function GetNumber(Request $request)
    {
        $phoneNumber = intval($request->phone_number);

        $verificationCode = mt_rand(10000, 99999);
        $smsController = new SmsController();
        $smsController->verificationCode($phoneNumber, $verificationCode);

        Register::create([
            'number' => $phoneNumber,
            'verification_code' => $verificationCode,
        ]);

        CleanUpVerificationCodesJob::dispatch()->delay(now()->addMinutes(3));

        return response()->json(['message' => 'Verification code has been sent.']);
    }

    public function GetCodeSent(Request $request)
    {
        $phoneNumber = $request->phone_number;
        $verificationCode = $request->verification_code;

        $register = Register::where('number', $phoneNumber)
            ->where('verification_code', $verificationCode)
            ->first();

        if (!$register || $register->created_at < now()->subMinutes(3)) {
            return response()->json(['message' => 'Invalid or expired verification code.'], 401);
        }

        $user = User::updateOrCreate([
            'phone_number' => $phoneNumber,
        ]);
        $user->assignRole('Customer');
        $token = $user->createToken('UserToken')->plainTextToken;

        return response()->json(['token' => $token, 'message' => 'Token created successfully.']);
    }

    public function GetInformation(Request $request)
    {
        $fullName = $request->full_name;
        $Email = $request->Email;

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->update([
            'full_name' => $fullName,
            'Email' => $Email,
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

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'logout' => 'Goodbye'
        ]);
    }

    public function EmailPassword(Request $request)
    {
        $Email = $request->Email;

        $code = mt_rand(100000, 999999);

        Register::create([
            'Email' => $Email,
            'verification_code' => $code,
        ]);

        Mail::to($Email)->send(new VerificationCodEmail($code));

        return response()->json([
            'message' => 'Verification code sent successfully',
        ]);
    }

    public function VerifyCode(Request $request)
    {
        $email = $request->Email;
        $code = $request->code;

        $record = Register::where('Email', $email)
            ->where('verification_code', $code)
            ->first();

        if (!$record) {
            return response()->json([
                'error' => 'Invalid verification code',
            ], 400);
        }

        $createdAt = Carbon::parse($record->created_at);
        $now = Carbon::now();
        $codeExpiration = $createdAt->addMinutes(3);

        if ($now > $codeExpiration) {
            $record->delete();
            return response()->json([
                'error' => 'Verification code has expired',
            ], 400);
        }

        $record->delete();

        $user = User::firstOrNew(['Email' => $email]);

        if (!$user->exists) {
            $user->full_name = 'Default Full Name';
            $user->Password = Hash::make('default_password');
            $user->save();
        }

        $token = $user->createToken('UserToken')->plainTextToken;

        return response()->json([
            'message' => 'Verification code is valid',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function UpdatePassword(Request $request)
    {
        $Email = $request->Email;
        $newPassword = $request->new_Password;

        $user = User::where('Email', $Email)->first();

        $hashedPassword = Hash::make($newPassword);

        $user->update(['Password' => $hashedPassword]);

        return response()->json([
            'message' => 'Password updated successfully',
            'user' => $user
        ]);
    }
}
