<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function list()
    {
        $customers = User::role('Customer')
            ->select('id', 'full_name', 'phone_number', 'status')
            ->get();

        return response()->json([
            'customers' => $customers
        ]);
    }

    public function operation($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $orders = $user->orders()->with('reserves.sans.product')->get();
            $countOrders = $user->orders->count();

            return response()->json([
                'user' => $user,
                'orders' => $orders,
                'count_orders' => $countOrders,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function blockOrActive($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $newStatus = ($user->status == 'Active') ? 'Block' : 'Active';

        $user->update([
            'status' => $newStatus
        ]);

        return response()->json(['message' => "The user's Status has been changed to $newStatus"]);
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update($request->all());

        return response()->json([
            'message' => 'User information updated successfully',
            'user' => $user
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update($request->all());

        return response()->json([
            'message' => 'User information updated successfully',
            'user' => $user
        ]);
    }
}
