<template>
    <div
        class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-16"
    >
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <span class="text-4xl">🇩🇪</span>
                <h1 class="text-2xl font-bold text-gray-900 mt-3">
                    إنشاء حساب جديد
                </h1>
                <p class="text-gray-500 mt-1">انضم لعائلة German Time</p>
            </div>

            <div
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8"
            >
                <!-- Google -->
                <button
                    @click="loginWithGoogle"
                    class="w-full flex items-center justify-center gap-3 border-2 border-gray-200 rounded-xl py-3 font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all mb-6"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path
                            fill="#4285F4"
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                        />
                        <path
                            fill="#34A853"
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                        />
                        <path
                            fill="#FBBC05"
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                        />
                        <path
                            fill="#EA4335"
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                        />
                    </svg>
                    متابعة بحساب Google
                </button>

                <div class="flex items-center gap-3 mb-6">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400 font-medium"
                        >أو بالإيميل</span
                    >
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >الاسم الكامل *</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="اسمك هنا"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50 focus:bg-white transition-all"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >الإيميل *</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="example@email.com"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50 focus:bg-white transition-all"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >الباسورد *</label
                        >
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="8 أحرف على الأقل"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50 focus:bg-white transition-all"
                        />
                    </div>
                </div>

                <p v-if="error" class="text-red-500 text-sm text-center mt-4">
                    {{ error }}
                </p>

                <button
                    @click="handleRegister"
                    :disabled="loading"
                    class="mt-6 w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all disabled:opacity-40"
                >
                    {{ loading ? "جاري التسجيل..." : "إنشاء حساب" }}
                </button>

                <p class="text-center text-sm text-gray-500 mt-4">
                    عندك حساب؟
                    <RouterLink
                        to="/login"
                        class="text-blue-600 font-semibold hover:underline"
                        >سجّل الدخول</RouterLink
                    >
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuth } from "@/stores/auth";

const router = useRouter();
const { register, loginWithGoogle } = useAuth();

const form = ref({ name: "", email: "", password: "" });
const loading = ref(false);
const error = ref("");

async function handleRegister() {
    if (!form.value.name || !form.value.email || !form.value.password) {
        error.value = "برجاء ملء جميع الحقول";
        return;
    }
    if (form.value.password.length < 8) {
        error.value = "الباسورد لازم يكون 8 أحرف على الأقل";
        return;
    }
    loading.value = true;
    error.value = "";
    try {
        await register(form.value.name, form.value.email, form.value.password);
        router.push("/");
    } catch (err) {
        error.value = err?.response?.data?.message || "حصل خطأ في التسجيل";
    } finally {
        loading.value = false;
    }
}
</script>
