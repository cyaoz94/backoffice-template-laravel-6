<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ConstantController extends Controller
{

    public function __invoke(Request $request)
    {
        return response()->json([
            'code' => 0,
            'data' => [
                'roles' => $this->getRoles(),
                'permissions' => $this->getPermissions(),
            ]
        ]);
    }

    private function getPermissions()
    {
        return Permission::all('name')->pluck('name');
    }

    private function getRoles()
    {
        return Role::all('id', 'name');
    }
}
