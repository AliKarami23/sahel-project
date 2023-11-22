<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function List()
    {
        $Customers = User::role('customer')->get();

        return response()->json([
            'Customer' => $Customers
        ]);
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
