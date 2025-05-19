<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Admins",
 *     description="Manage admin users within an organization"
 * )
 */
class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admins",
     *     summary="List all admins of the authenticated user's organization",
     *     tags={"Admins"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of admins",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Admin"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $orgId = $request->user()->organization_id;
        return Admin::where('organization_id', $orgId)->get();
    }

    /**
     * @OA\Post(
     *     path="/api/admins",
     *     summary="Create a new admin in the authenticated user's organization",
     *     tags={"Admins"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="Alice Smith"),
     *             @OA\Property(property="email", type="string", format="email", example="alice@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Admin created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Admin")
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/admins/{id}",
     *     summary="Get details of a specific admin",
     *     tags={"Admins"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Admin ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admin details",
     *         @OA\JsonContent(ref="#/components/schemas/Admin")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Admin not found"
     *     )
     * )
     */
    public function show(Request $request, $id)
    {
        $admin = Admin::where('id', $id)
                      ->where('organization_id', $request->user()->organization_id)
                      ->firstOrFail();

        return response()->json($admin);
    }

    /**
     * @OA\Put(
     *     path="/api/admins/{id}",
     *     summary="Update a specific admin's information",
     *     tags={"Admins"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Admin ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="email", type="string", format="email", example="updated@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admin updated",
     *         @OA\JsonContent(ref="#/components/schemas/Admin")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Admin not found"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/admins/{id}",
     *     summary="Delete an admin",
     *     tags={"Admins"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Admin ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admin deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Admin deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Admin not found"
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $admin = Admin::where('id', $id)
                      ->where('organization_id', $request->user()->organization_id)
                      ->firstOrFail();

        $admin->delete();

        return response()->json(['message' => 'Admin deleted']);
    }
}
