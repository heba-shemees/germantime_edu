<template>
    <div class="py-16 px-4 overflow-hidden">
        <div class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
                <div
                    v-motion
                    :initial="{ opacity: 0, x: -50 }"
                    :enter="{ opacity: 1, x: 0, transition: { duration: 700 } }"
                    class="flex justify-center"
                >
                    <div class="relative">
                        <div
                            class="w-72 h-72 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300"
                        >
                            <img
                                :src="teacherImage"
                                alt="المعلمة نور"
                                class="w-full h-full object-cover object-top"
                            />
                        </div>
                        <div
                            class="absolute -top-3 -right-3 bg-white rounded-2xl shadow-md px-3 py-1 text-sm font-semibold text-blue-700 border border-blue-100 animate-bounce"
                        >
                            A1 → B2
                        </div>
                        <div
                            class="absolute -bottom-3 -left-3 bg-white rounded-2xl shadow-md px-3 py-1 text-sm font-semibold text-green-600 border border-green-100"
                        >
                            ⭐ 4.9
                        </div>
                    </div>
                </div>
                <div
                    v-motion
                    :initial="{ opacity: 0, x: 50 }"
                    :enter="{
                        opacity: 1,
                        x: 0,
                        transition: { duration: 700, delay: 200 },
                    }"
                >
                    <div
                        class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium mb-4"
                    >
                        ✨ {{ t("about_badge") }}
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        {{ t("about_title") }}
                    </h1>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        {{ t("about_desc") }}
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-16">
                <div
                    v-for="(s, i) in stats"
                    :key="s.labelKey"
                    v-motion
                    :initial="{ opacity: 0, y: 30 }"
                    :visible="{
                        opacity: 1,
                        y: 0,
                        transition: { duration: 500, delay: i * 100 },
                    }"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 p-6 text-center"
                >
                    <div class="text-3xl font-extrabold text-blue-700 mb-1">
                        {{ s.value }}
                    </div>
                    <div class="text-sm text-gray-500">{{ t(s.labelKey) }}</div>
                </div>
            </div>
            <div
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :visible="{ opacity: 1, y: 0, transition: { duration: 600 } }"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-8"
            >
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    {{ t("about_style_title") }}
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div
                        v-for="item in style"
                        :key="item.title"
                        class="flex gap-3 items-start hover:bg-blue-50 p-3 rounded-xl transition-colors duration-200"
                    >
                        <span class="text-2xl flex-shrink-0">{{
                            item.icon
                        }}</span>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-sm">
                                {{ item.title }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ item.desc }}
                            </p>
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
                    to="/start"
                    class="bg-blue-600 text-white px-10 py-4 rounded-xl font-bold text-lg hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200 inline-block shadow-lg shadow-blue-200"
                >
                    {{ t("about_cta") }}
                </RouterLink>
            </div>
        </div>
    </div>
</template>
<script setup>
const teacherImage = "/images/teacher.jpg";
import { useLang } from "@/i18n";
const { t } = useLang();
const stats = [
    { value: "200+", labelKey: "stat_students" },
    { value: "3+", labelKey: "stat_experience" },
    { value: "A1–B2", labelKey: "stat_levels" },
    { value: "98%", labelKey: "stat_satisfaction" },
];
const style = [
    {
        icon: "🗣️",
        title: "محادثة من اليوم الأول",
        desc: "مش هتستني تحفظي القواعد عشان تتكلمي",
    },
    {
        icon: "🎯",
        title: "أمثلة عملية",
        desc: "كل درس فيه تطبيق حقيقي من الحياة",
    },
    { icon: "📝", title: "واجبات مخصصة", desc: "مواد مناسبة لمستواكِ بالضبط" },
    {
        icon: "💬",
        title: "دعم خارج الحصص",
        desc: "تقدري تسأليني على واتساب في أي وقت",
    },
];
</script>
