<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function List()
    {
        $Customers = User::role('Customer')
            ->select('id', 'FullName', 'PhoneNumber')
            ->get();

        return response()->json([
            'Customer' => $Customers
        ]);
    }

    public function operation()
    {
//        اسم و شماره و ایمیل و تعداد خرید ها count(order) و فاکتور ها کامل
    }


    public function Delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }

    public function Obstruction($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $user->update([
            'Status' => 'blocked'
        ]);

        return response()->json(['message' => 'The user was blocked']);
    }

    public function Edit(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Customer not found'], 404);
        }


        $user->update($request->all());

        return response()->json([
            'message' => 'Customer information updated successfully',
            'customer' => $user
        ]);
    }

    public function Update(Request $request)
    {
        $user = Auth::user();

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
