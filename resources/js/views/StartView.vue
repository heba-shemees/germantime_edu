<template>
    <div
        class="min-h-screen bg-gradient-to-b from-blue-50 to-white flex items-center overflow-hidden"
    >
        <div class="max-w-2xl mx-auto px-4 py-20 text-center">
            <div
                v-motion
                :initial="{ opacity: 0, scale: 0.5 }"
                :enter="{ opacity: 1, scale: 1, transition: { duration: 600 } }"
                class="text-6xl mb-6"
            >
                🇩🇪
            </div>
            <h1
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :enter="{
                    opacity: 1,
                    y: 0,
                    transition: { duration: 600, delay: 100 },
                }"
                class="text-4xl font-bold text-gray-900 mb-4"
            >
                {{ t("start_title") }}
            </h1>
            <p
                v-motion
                :initial="{ opacity: 0, y: 20 }"
                :enter="{
                    opacity: 1,
                    y: 0,
                    transition: { duration: 600, delay: 200 },
                }"
                class="text-gray-500 text-lg leading-relaxed mb-10 max-w-lg mx-auto"
            >
                {{ t("start_desc") }}
            </p>
            <div class="grid grid-cols-3 gap-4 mb-10">
                <div
                    v-for="(b, i) in benefits"
                    :key="i"
                    v-motion
                    :initial="{ opacity: 0, y: 30 }"
                    :enter="{
                        opacity: 1,
                        y: 0,
                        transition: { duration: 500, delay: 300 + i * 100 },
                    }"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300"
                >
                    <div class="text-2xl mb-2">{{ b.icon }}</div>
                    <p class="text-sm text-gray-600 font-medium">
                        {{ t(b.key) }}
                    </p>
                </div>
            </div>
            <div
                v-motion
                :initial="{ opacity: 0, scale: 0.8 }"
                :enter="{
                    opacity: 1,
                    scale: 1,
                    transition: { duration: 500, delay: 600 },
                }"
            >
                <RouterLink
                    to="/test"
                    class="bg-blue-600 text-white px-10 py-4 rounded-xl font-bold text-xl hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200 inline-block shadow-lg shadow-blue-200"
                >
                    {{ t("start_btn") }}
                </RouterLink>
                <p class="text-xs text-gray-400 mt-6">{{ t("start_free") }}</p>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-100">
                <p class="text-sm text-gray-500 mb-4">{{ t("start_know") }}</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <RouterLink
                        v-for="level in levels"
                        :key="level"
                        :to="`/courses?level=${level}`"
                        class="border-2 border-blue-600 text-blue-600 py-2 px-5 rounded-xl text-sm font-semibold hover:bg-blue-50 transition-all"
                    >
                        {{ level }}
                    </RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { useLang } from "@/i18n";
const { t } = useLang();
const benefits = [
    { icon: "⚡", key: "benefit_1" },
    { icon: "📊", key: "benefit_2" },
    { icon: "🎯", key: "benefit_3" },
];
const levels = ["A1", "A2", "B1", "B2"];
</script>
