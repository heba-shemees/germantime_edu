<template>
    <nav
        class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100"
    >
        <div
            class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between"
        >
            <RouterLink
                to="/"
                class="flex items-center gap-2 font-bold text-xl text-blue-700"
            >
                <span class="text-2xl">🇩🇪</span>
                <span>German Time</span>
            </RouterLink>

            <!-- Desktop -->
            <div
                class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600"
            >
                <RouterLink
                    to="/courses"
                    class="hover:text-blue-600 transition-colors"
                    >{{ t("nav_courses") }}</RouterLink
                >
                <RouterLink
                    to="/private-lessons"
                    class="hover:text-blue-600 transition-colors"
                    >{{ t("nav_private") }}</RouterLink
                >
                <RouterLink
                    to="/about"
                    class="hover:text-blue-600 transition-colors"
                    >{{ t("nav_about") }}</RouterLink
                >
                <RouterLink
                    to="/contact"
                    class="hover:text-blue-600 transition-colors"
                    >{{ t("nav_contact") }}</RouterLink
                >

                <!-- Language switcher -->
                <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1">
                    <button
                        @click="setLang('ar')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200"
                        :class="
                            lang === 'ar'
                                ? 'bg-white shadow text-blue-700'
                                : 'text-gray-500 hover:text-gray-700'
                        "
                    >
                        عربي
                    </button>
                    <button
                        @click="setLang('de')"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200"
                        :class="
                            lang === 'de'
                                ? 'bg-white shadow text-blue-700'
                                : 'text-gray-500 hover:text-gray-700'
                        "
                    >
                        Deutsch
                    </button>
                </div>

                <!-- لو مش مسجل -->
                <template v-if="!isLoggedIn">
                    <RouterLink
                        to="/login"
                        class="text-blue-600 font-semibold hover:text-blue-700 transition-colors"
                    >
                        تسجيل الدخول
                    </RouterLink>
                    <RouterLink
                        to="/start"
                        class="bg-blue-600 text-white py-2 px-5 rounded-xl text-sm font-semibold hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200"
                    >
                        {{ t("nav_test") }}
                    </RouterLink>
                </template>

                <!-- لو مسجل -->
                <template v-else>
                    <RouterLink
                        to="/start"
                        class="bg-blue-600 text-white py-2 px-5 rounded-xl text-sm font-semibold hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200"
                    >
                        {{ t("nav_test") }}
                    </RouterLink>

                    <!-- Profile dropdown -->
                    <div class="relative" ref="profileRef">
                        <button
                            @click="profileOpen = !profileOpen"
                            class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 rounded-xl px-3 py-2 transition-all"
                        >
                            <div
                                class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold"
                            >
                                {{
                                    user?.name?.charAt(0)?.toUpperCase() || "؟"
                                }}
                            </div>
                            <span class="text-sm font-semibold text-gray-700">{{
                                user?.name?.split(" ")[0]
                            }}</span>
                            <svg
                                class="w-4 h-4 text-gray-400 transition-transform"
                                :class="profileOpen ? 'rotate-180' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div
                            v-if="profileOpen"
                            class="absolute left-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50"
                        >
                            <RouterLink
                                to="/profile"
                                @click="profileOpen = false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                👤 البروفايل
                            </RouterLink>
                            <RouterLink
                                to="/my-bookings"
                                @click="profileOpen = false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                📋 حجوزاتي
                            </RouterLink>
                            <RouterLink
                                v-if="isAdmin"
                                to="/admin"
                                @click="profileOpen = false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-blue-600 hover:bg-blue-50 transition-colors font-semibold"
                            >
                                🎓 لوحة التحكم
                            </RouterLink>
                            <hr class="my-1 border-gray-100" />
                            <button
                                @click="handleLogout"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors w-full text-right"
                            >
                                🚪 تسجيل الخروج
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Mobile -->
            <div class="md:hidden flex items-center gap-2">
                <div class="flex items-center gap-1 bg-gray-100 rounded-xl p-1">
                    <button
                        @click="setLang('ar')"
                        class="px-2 py-1 rounded-lg text-xs font-bold transition-all"
                        :class="
                            lang === 'ar'
                                ? 'bg-white shadow text-blue-700'
                                : 'text-gray-500'
                        "
                    >
                        ع
                    </button>
                    <button
                        @click="setLang('de')"
                        class="px-2 py-1 rounded-lg text-xs font-bold transition-all"
                        :class="
                            lang === 'de'
                                ? 'bg-white shadow text-blue-700'
                                : 'text-gray-500'
                        "
                    >
                        DE
                    </button>
                </div>

                <!-- Profile icon mobile -->
                <RouterLink
                    v-if="isLoggedIn"
                    to="/profile"
                    class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold"
                >
                    {{ user?.name?.charAt(0)?.toUpperCase() || "؟" }}
                </RouterLink>

                <button
                    class="p-2 rounded-lg hover:bg-gray-100"
                    @click="menuOpen = !menuOpen"
                >
                    <svg
                        v-if="!menuOpen"
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div
            v-if="menuOpen"
            class="md:hidden border-t border-gray-100 bg-white px-4 py-4 space-y-3"
        >
            <RouterLink
                to="/courses"
                class="block py-2 text-gray-700 font-medium"
                @click="menuOpen = false"
                >{{ t("nav_courses") }}</RouterLink
            >
            <RouterLink
                to="/private-lessons"
                class="block py-2 text-gray-700 font-medium"
                @click="menuOpen = false"
                >{{ t("nav_private") }}</RouterLink
            >
            <RouterLink
                to="/about"
                class="block py-2 text-gray-700 font-medium"
                @click="menuOpen = false"
                >{{ t("nav_about") }}</RouterLink
            >
            <RouterLink
                to="/contact"
                class="block py-2 text-gray-700 font-medium"
                @click="menuOpen = false"
                >{{ t("nav_contact") }}</RouterLink
            >
            <RouterLink
                to="/start"
                class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold block text-center"
                @click="menuOpen = false"
            >
                {{ t("nav_test") }}
            </RouterLink>
            <template v-if="!isLoggedIn">
                <RouterLink
                    to="/login"
                    class="block py-2 text-blue-600 font-semibold text-center"
                    @click="menuOpen = false"
                >
                    تسجيل الدخول
                </RouterLink>
            </template>
            <template v-else>
                <RouterLink
                    to="/profile"
                    class="block py-2 text-gray-700 font-medium"
                    @click="menuOpen = false"
                    >👤 البروفايل</RouterLink
                >
                <RouterLink
                    to="/my-bookings"
                    class="block py-2 text-gray-700 font-medium"
                    @click="menuOpen = false"
                    >📋 حجوزاتي</RouterLink
                >
                <RouterLink
                    v-if="isAdmin"
                    to="/admin"
                    class="block py-2 text-blue-600 font-semibold"
                    @click="menuOpen = false"
                    >🎓 لوحة التحكم</RouterLink
                >
                <button
                    @click="handleLogout"
                    class="block w-full text-right py-2 text-red-600 font-medium"
                >
                    🚪 تسجيل الخروج
                </button>
            </template>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import { useLang } from "@/i18n";
import { useAuth } from "@/stores/auth";

const { lang, t, setLang } = useLang();
const { user, isLoggedIn, isAdmin, logout } = useAuth();
const router = useRouter();
const menuOpen = ref(false);
const profileOpen = ref(false);
const profileRef = ref(null);

router.afterEach(() => {
    menuOpen.value = false;
    profileOpen.value = false;
});

async function handleLogout() {
    await logout();
    profileOpen.value = false;
    router.push("/");
}

// Close dropdown on outside click
function handleClickOutside(e) {
    if (profileRef.value && !profileRef.value.contains(e.target)) {
        profileOpen.value = false;
    }
}

onMounted(() => document.addEventListener("click", handleClickOutside));
onUnmounted(() => document.removeEventListener("click", handleClickOutside));
</script>
