<?php

namespace App\Http\Controllers;

use App\Models\PhoneNumber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Sms\app\Http\Controllers\SmsController;
use Illuminate\Validation\ValidationException;


class RegisterController extends Controller
{
    public function GetNumber(Request $request)
    {
        $Phone_Number = $request->Phone_Number;

        $Verification_Code = mt_rand(10000, 99999);
        $tmp = new SmsController();
        $tmp->VerificationCode($Phone_Number,$Verification_Code);

        PhoneNumber::create([
            'number' => $Phone_Number,
            'verification_code' => $Verification_Code,
        ]);

        return response()->json(['message' => 'Verification code has been sent.']);
    }



    public function GetCodeSent(Request $request)
    {
        $Phone_Number = $request->Phone_Number;
        $verificationCode = $request->verification_code;

        $phoneNumber = PhoneNumber::where('number', $Phone_Number)
            ->where('verification_code', $verificationCode)
            ->first();

        if (!$phoneNumber) {
            return response()->json(['message' => 'Invalid verification code.'], 401);
        }

        $token = $phoneNumber->createToken('UserToken')->plainTextToken;

        $user = $phoneNumber->user;
        $user->assignRole('Customer');

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

        return response()->json(['message' => 'User information updated successfully.']);
    }


    public function AdminLogin(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function Logout(Request $request)
    {

        $request->user()->tokens()->delete();
        return response()->json([
            'Logout' => 'Goodbye'
        ]);
    }

    public function ListCustomer()
    {
        $Customer = User::where('Role', 'Customer')->get();
        return response()->json([
            'Customer' => $Customer
        ]);
    }


    public function DeleteCustomer($id)
    {
        $user = User::where('Role', 'Customer')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }

    public function EditCustomer(Request $request, $id)
    {
        $user = User::where('Role', 'Customer')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Customer not found'], 404);
        }


        $user->update($request->all());

        return response()->json([
            'message' => 'Customer information updated successfully',
            'customer' => $user
        ]);
    }



}
