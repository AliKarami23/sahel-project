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
            ->select('id', 'Full_Name', 'Phone_Number','Status')
            ->get();

        return response()->json([
            'Customer' => $Customers
        ]);
    }

    public function operation($id)
    {
        try {
            $user = User::find($id);

            $orders = $user->orders()->with('reserves.sans.product')->get();

            $CountOrders = $user->orders->count();

            return response()->json([
                'user' => $user,
                'orders' => $orders,
                'CountOrders' => $CountOrders,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

    public function BlockOrActive($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $newStatus = ($user->Status == 'Active') ? 'Block' : 'Active';

        $user->update([
            'Status' => $newStatus
        ]);

        return response()->json(['message' => "The user's status has been changed to $newStatus"]);
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
    public function Show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json([
            'customer' => $user
        ]);
    }

    public function Update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }


        $user->update($request->all());

        return response()->json([
            'message' => 'User information updated successfully',
            'User' => $user
        ]);
    }


}
