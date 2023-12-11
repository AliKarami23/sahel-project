<?php

namespace Modules\Role\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Role\app\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function create(RoleRequest $request){



        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'api'
        ]);

        $permissions = $request->permissions;
        $role->permissions()->sync($permissions);

        return response()->json(
            ['message' => 'Role is Add',
                'role' => $role,
                'permissions'=>$permissions
            ]);

    }
    public function edit(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);

        $role->update([
            'name' => $request->name,
        ]);

        $permissions = $request->permissions;
        $role->permissions()->sync($permissions);

        return response()->json(['message' => 'Role is Edit', 'role' => $role]);
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        $role->delete();

        return response()->json(['message' => 'Role is Delete']);
    }

    public function list(){

        $roles = Role::all();
        $permission = Permission::all();

        return response()->json([
            'roles' => $roles,
            'permission' => $permission
        ]);
    }
}
