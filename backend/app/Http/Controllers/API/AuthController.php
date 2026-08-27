<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    /**
     * @OA\Post(
     *      path="/auth/register",
     *      operationId="registerUser",
     *      tags={"Auth"},
     *      summary="Register Penumpang Baru",
     *      description="Mendaftar akun pelanggan baru untuk aplikasi mobile & web",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","phone","password","password_confirmation"},
     *              @OA\Property(property="name", type="string", example="Ahmad Budi"),
     *              @OA\Property(property="email", type="string", format="email", example="budi@example.com"),
     *              @OA\Property(property="phone", type="string", example="081234567890"),
     *              @OA\Property(property="password", type="string", format="password", example="Password123!"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="Password123!")
     *          )
     *      ),
     *      @OA\Response(response=201, description="Registrasi berhasil"),
     *      @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:15', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $user->assignRole($customerRole);

        $token = $user->createToken('mobile-auth')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
                'needs_verification' => true,
            ],
        ], 201);
    }

    /**
     * @OA\Post(
     *      path="/auth/login",
     *      operationId="loginUser",
     *      tags={"Auth"},
     *      summary="Login Penumpang",
     *      description="Login menggunakan email/phone & password untuk memperoleh Bearer token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="budi@example.com"),
     *              @OA\Property(property="password", type="string", example="Password123!")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Login berhasil"),
     *      @OA\Response(response=401, description="Kredensial salah")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('mobile-auth')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token,
                'needs_verification' => !$user->hasPhoneVerified(),
            ],
        ]);
    }

    public function sendOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'identifier' => ['required', 'string'],
            'method' => ['required', 'in:whatsapp,email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $this->otpService->generate($request->identifier, $request->method);

            $destination = $request->method === 'email' ? 'email' : 'nomor WhatsApp';

            return response()->json([
                'success' => true,
                'message' => "Kode OTP telah dikirim ke {$destination}",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'identifier' => ['required', 'string'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $isValid = $this->otpService->verify($request->identifier, $request->otp);

        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid atau kadaluarsa',
            ], 400);
        }

        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'phone_verified_at' => now(),
                'is_verified' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil',
        ]);
    }

    public function resendOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'identifier' => ['required', 'string'],
            'method' => ['required', 'in:whatsapp,email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $this->otpService->generate($request->identifier, $request->method);

            $destination = $request->method === 'email' ? 'email' : 'nomor WhatsApp';

            return response()->json([
                'success' => true,
                'message' => "Kode OTP baru telah dikirim ke {$destination}",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->load('bookings');

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['sometimes', 'string', 'max:15', 'unique:users,phone,' . $user->id],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->update($request->only(['name', 'email', 'phone']));

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user->fresh(),
        ]);
    }
}
