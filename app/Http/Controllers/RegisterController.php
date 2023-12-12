<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\EmailPasswordRequest;
use App\Http\Requests\GetCodeSentRequest;
use App\Http\Requests\GetInformationRequest;
use App\Http\Requests\GetNumberRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\VerifyCodeRequest;
use App\Jobs\CleanUpVerificationCodesJob;
use App\Mail\VerificationCodeMail;
use App\Models\Register;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;


class RegisterController extends Controller
{
    public function getNumber(GetNumberRequest $request)
    {
        $phoneNumber = intval($request->phone_number);

        $verificationCode = mt_rand(10000, 99999);
        $smsController = new SmsController();
        $smsController->VerificationCode($phoneNumber, $verificationCode);

        Register::create([
            'number' => $phoneNumber,
            'verification_code' => $verificationCode,
        ]);

        CleanUpVerificationCodesJob::dispatch()->delay(now()->addMinutes(3));

        return response()->json(['message' => 'Verification code has been sent.']);
    }

    public function getCodeSent(GetCodeSentRequest $request)
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

    public function getInformation(GetInformationRequest $request)
    {
        $fullName = $request->full_name;
        $email = $request->email;

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->update([
            'full_name' => $fullName,
            'email' => $email,
        ]);

        return response()->json([
            'message' => 'User information updated successfully.',
            'user' => $user
        ]);
    }

    public function adminLogin(AdminLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials']
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
            'logout' => 'Goodbye'
        ]);
    }

    public function emailPassword(EmailPasswordRequest $request)
    {
        $email = $request->email;

        $code = mt_rand(100000, 999999);

        Register::create([
            'email' => $email,
            'verification_code' => $code,
        ]);

        Mail::to($email)->send(new VerificationCodEmail($code));

        return response()->json([
            'message' => 'Verification code sent successfully',
        ]);
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        $email = $request->email;
        $code = $request->code;

        $record = Register::where('email', $email)
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

        $user = User::firstOrNew(['email' => $email]);

        if (!$user->exists) {
            $user->full_name = 'Default full Name';
            $user->password = Hash::make('default_password');
            $user->save();
        }

        $token = $user->createToken('UserToken')->plainTextToken;

        return response()->json([
            'message' => 'Verification code is valid',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $email = $request->email;
        $newPassword = $request->new_password;

        $user = User::where('email', $email)->first();

        $hashedPassword = Hash::make($newPassword);

        $user->update(['password' => $hashedPassword]);

        return response()->json([
            'message' => 'Password updated successfully',
            'user' => $user
        ]);
    }
}
