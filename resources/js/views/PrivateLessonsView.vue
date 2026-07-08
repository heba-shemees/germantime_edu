<template>
    <div class="py-16 px-4 overflow-hidden">
        <div class="max-w-3xl mx-auto">
            <div
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :enter="{ opacity: 1, y: 0, transition: { duration: 600 } }"
                class="text-center mb-12"
            >
                <div class="text-5xl mb-4 animate-bounce">🎯</div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ t("private_title") }}
                </h1>
                <p class="text-gray-500 text-lg">{{ t("private_desc") }}</p>
            </div>
            <div class="grid md:grid-cols-3 gap-5 mb-12">
                <div
                    v-for="(b, i) in benefits"
                    :key="i"
                    v-motion
                    :initial="{ opacity: 0, y: 30 }"
                    :enter="{
                        opacity: 1,
                        y: 0,
                        transition: { duration: 500, delay: i * 100 },
                    }"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 p-6 text-center"
                >
                    <div class="text-3xl mb-3">{{ b.icon }}</div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ b.title }}</h3>
                    <p class="text-sm text-gray-500">{{ b.desc }}</p>
                </div>
            </div>
            <div class="space-y-4 mb-10">
                <div
                    v-for="(pkg, i) in packages"
                    :key="pkg.name"
                    v-motion
                    :initial="{ opacity: 0, x: -30 }"
                    :visible="{
                        opacity: 1,
                        x: 0,
                        transition: { duration: 500, delay: i * 100 },
                    }"
                    class="bg-white rounded-2xl border-2 p-5 flex items-center justify-between cursor-pointer transition-all duration-300 hover:shadow-md"
                    :class="
                        selected === pkg.name
                            ? 'border-blue-500 bg-blue-50 scale-[1.02]'
                            : 'border-gray-100 hover:border-blue-200'
                    "
                    @click="selected = pkg.name"
                >
                    <div class="flex items-center gap-4">
                        <div
                            class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                            :class="
                                selected === pkg.name
                                    ? 'border-blue-500 bg-blue-500'
                                    : 'border-gray-300'
                            "
                        >
                            <div
                                v-if="selected === pkg.name"
                                class="w-2 h-2 rounded-full bg-white"
                            ></div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">
                                {{ pkg.name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ pkg.desc }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-xl text-blue-700">
                            {{ pkg.price }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ t("currency") }}
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-motion
                :initial="{ opacity: 0, scale: 0.9 }"
                :visible="{
                    opacity: 1,
                    scale: 1,
                    transition: { duration: 500 },
                }"
                class="text-center"
            >
                <RouterLink
                    to="/booking?level=private"
                    class="bg-blue-600 text-white px-12 py-4 rounded-xl font-bold text-lg hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200 inline-block shadow-lg shadow-blue-200"
                >
                    {{ t("private_btn") }}
                </RouterLink>
                <p class="text-xs text-gray-400 mt-4">
                    {{ t("private_note") }}
                </p>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from "vue";
import { useLang } from "@/i18n";
const { t } = useLang();
const selected = ref("حصة واحدة");
const benefits = [
    {
        icon: "👤",
        title: "اهتمام فردي",
        desc: "الحصة كلها ليكِ — مفيش طالبات تانيين",
    },
    { icon: "🕐", title: "مواعيد مرنة", desc: "اختاري الوقت المناسب ليكِ" },
    { icon: "📋", title: "منهج مخصص", desc: "بنصمم المحتوى على حسب أهدافك" },
];
const packages = [
    { name: "حصة واحدة", desc: "60 دقيقة — مناسبة للتجربة", price: 250 },
    { name: "باقة 5 حصص", desc: "5 × 60 دقيقة — توفير 10%", price: 1125 },
    { name: "باقة 10 حصص", desc: "10 × 60 دقيقة — توفير 15%", price: 2125 },
];
</script>
