<?php

namespace App\Http\Controllers;

use App\Filters\UserFilter;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends CrudController
{
    public function __construct(Request $request)
    {
        $this->modelClass = User::class;
        $this->filterClass = UserFilter::class;
        
        parent::__construct($request);
    }

    public function store(Request $request, array $validationRules = [])
    {

        $request->validate([
            'username' => 'required|alpha_num|min:4',
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|alpha_num|min:6',
            'role_id' => 'exists:roles,id'
        ]);
        $request['password'] = bcrypt($request->input('password'));

        DB::transaction(function () use ($request) {
            $user = User::create($request->all());

            $role = Role::find($request->input('role_id'));
            $this->authorize('assignRole', $role); // authorize with RolePolicy

            $user->assignRole($role);
        });

        return response()->json([
            'code' => 0,
            'message' => 'Created Successfully.',
        ]);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->with('roles')->first();

        return response()->json([
            'code' => 0,
            'data' => $user,
        ]);
    }

    public function update(Request $request, $id, array $validationRules = [])
    {
        $request->validate([
            'username' => 'required|alpha_num|min:4|unique:users,username,' . $id,
            'name' => 'required',
            'email' => "required|unique:users,email,$id|email",
            'password' => 'alpha_num|min:6',
        ]);

        if ($request->has('password')) {
            $request['password'] = bcrypt($request->input('password'));
        }

        $user = User::findOrFail($id);
        $this->authorize('update', $user); // authorize with UserPolicy

        $user->fill($request->all());
        $user->save();

        return response()->json([
            'code' => 0,
            'message' => 'Updated Successfully.',
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user); // authorize with UserPolicy
        $user->delete();

        return response()->json([
            'code' => 0,
            'message' => 'Deleted Successfully.',
        ]);
    }

}
