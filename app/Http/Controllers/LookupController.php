<?php

namespace App\Http\Controllers;

class LookupController extends Controller
{
    public function getUserPermissions()
    {
        $permissions = auth()->user()->getAllPermissions()->pluck('name');

        return response()->json([
            'code' => 0,
            'data' => $permissions
        ]);
    }
}
