<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Admin Authentication",
 *     description="APIs for admin login, logout, and profile"
 */
class AdminAuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/admin/login",
     *     tags={"Admin Authentication"},
     *     summary="Login an admin",
     *     description="Authenticates an admin and returns an access token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|abcdef123456..."),
     *             @OA\Property(property="admin", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Admin User"),
     *                 @OA\Property(property="email", type="string", example="admin@example.com"),
     *                 @OA\Property(property="organization_id", type="integer", example=1),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials.")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'admin' => $admin->only(['id', 'name', 'email', 'organization_id']),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/me",
     *     tags={"Admin Authentication"},
     *     summary="Get logged in admin info",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Current authenticated admin",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Admin User"),
     *             @OA\Property(property="email", type="string", example="admin@example.com"),
     *             @OA\Property(property="organization_id", type="integer", example=1),
     *         )
     *     )
     * )
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * @OA\Post(
     *     path="/api/admin/logout",
     *     tags={"Admin Authentication"},
     *     summary="Logout current admin",
     *     security={{"sanctum":{}}},
     *     description="Revokes the current access token of the authenticated admin.",
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
