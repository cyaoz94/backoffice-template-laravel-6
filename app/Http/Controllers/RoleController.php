<?php

namespace App\Http\Controllers;

use App\Filters\RoleFilter;
use App\Http\Resources\RoleWithPermissionResource;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends CrudController
{
    public function __construct(Request $request)
    {
        $this->modelClass = Role::class;
        $this->filterClass = RoleFilter::class;

        parent::__construct($request);
    }

    public function store(Request $request, array $validationRules = [])
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array|exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->permissions);

        return response()->json([
            'code' => 0,
            'message' => 'Created Successfully.',
        ]);
    }

    public function show($id)
    {
        $role = Role::where('id', $id)->with('permissions')->first();

        return response()->json([
            'code' => 0,
            'data' => new RoleWithPermissionResource($role),
        ]);
    }

    public function update(Request $request, $id, array $validationRules = [])
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array|exists:permissions,name',
        ]);

        $role = Role::findOrFail($id);
        $this->authorize('update', $role); // authorize with RolePolicy

        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permissions'));

        return response()->json([
            'code' => 0,
            'message' => 'Updated Successfully.',
        ]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('delete', $role); // authorize with RolePolicy
        $role->delete();

        return response()->json([
            'code' => 0,
            'message' => 'Deleted Successfully.',
        ]);
    }
}
