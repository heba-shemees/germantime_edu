<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * تسجيل مستخدم جديد بالإيميل والباسورد
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.unique' => 'هذا البريد الإلكتروني مسجل بالفعل، برجاء تسجيل الدخول.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطأ في البيانات المدخلة',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم إنشاء الحساب بنجاح',
            'user'    => $this->formatUser($user),
            'token'   => $token,
        ], 201);
    }

    /**
     * تسجيل الدخول بالإيميل والباسورد (يشمل الأدمن أيضًا)
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطأ في البيانات المدخلة',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->password || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
            ], 401);
        }

        // إبطال التوكنات القديمة (اختياري لو عايز جلسة واحدة بس)
        // $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'user'    => $this->formatUser($user),
            'token'   => $token,
        ]);
    }

    /**
     * تسجيل الخروج (إبطال التوكن الحالي)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }

    /**
     * بيانات المستخدم الحالي (لتفعيل الجلسة عند فتح الموقع)
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $this->formatUser($request->user()),
        ]);
    }

    /**
     * توجيه المستخدم لصفحة جوجل لتسجيل الدخول
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    /**
     * استقبال رد جوجل بعد تسجيل الدخول، وإنشاء/تسجيل دخول المستخدم
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            \Log::error('Google login failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'فشل تسجيل الدخول عبر جوجل',
                'error'   => $e->getMessage(), // مؤقت للتشخيص بس - امسحه بعدين
            ], 401);
        }
        // ... الباقي زي ما هو

        // البحث عن المستخدم بالـ google_id أو بالإيميل (لو سجل قبل كده بشكل عادي)
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // ربط الحساب بجوجل لو كان مسجل بالإيميل قبل كده
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->avatar = $user->avatar ?: $googleUser->getAvatar();
                $user->save();
            }
        } else {
            $user = User::create([
                'name'      => $googleUser->getName() ?: $googleUser->getNickname(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'password'  => Hash::make(Str::random(24)), // باسورد عشوائي، مش هيستخدمه
                'is_admin'  => false,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Redirect لصفحة الفرونت مع التوكن، بدل إرجاع JSON خام للمتصفح
        $frontendUrl = config('app.frontend_url', config('app.url'));

        return redirect()->away(
            $frontendUrl . '/auth/google/callback?token=' . $token
        );
    }

    /**
     * تنسيق بيانات المستخدم قبل إرسالها للفرونت
     */
    private function formatUser(User $user): array
    {
        return [
            'id'     => $user->id,
            'name'   => $user->name,
            'email'  => $user->email,
            'avatar' => $user->avatar,
            'role'   => $user->is_admin ? 'admin' : 'user',
        ];
    }
}
