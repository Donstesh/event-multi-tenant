<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organization_id;
        return Admin::where('organization_id', $orgId)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins',
            'password' => 'required|string|min:6',
        ]);

        $admin = Admin::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'organization_id' => $request->user()->organization_id,
        ]);

        return response()->json($admin, 201);
    }

    public function show(Request $request, $id)
    {
        $admin = Admin::where('id', $id)
                      ->where('organization_id', $request->user()->organization_id)
                      ->firstOrFail();

        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::where('id', $id)
                      ->where('organization_id', $request->user()->organization_id)
                      ->firstOrFail();

        $data = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $admin->update($data);

        return response()->json($admin);
    }

    public function destroy(Request $request, $id)
    {
        $admin = Admin::where('id', $id)
                      ->where('organization_id', $request->user()->organization_id)
                      ->firstOrFail();

        $admin->delete();

        return response()->json(['message' => 'Admin deleted']);
    }
}
