<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <div class="text-4xl mb-4">⏳</div>
            <p class="text-gray-500">جاري تسجيل الدخول...</p>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAuth } from "@/stores/auth";

const router = useRouter();
const route = useRoute();
const { setSession } = useAuth();

onMounted(() => {
    const token = route.query.token;
    const user = route.query.user
        ? JSON.parse(decodeURIComponent(route.query.user))
        : null;

    if (token && user) {
        setSession(user, token);
        router.push(user.role === "admin" ? "/admin" : "/");
    } else {
        router.push("/login");
    }
});
</script>
