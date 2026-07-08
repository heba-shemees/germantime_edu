<template>
    <div class="py-16 px-4 overflow-hidden">
        <div class="max-w-2xl mx-auto text-center">
            <div
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :enter="{ opacity: 1, y: 0, transition: { duration: 600 } }"
            >
                <h1 class="text-3xl font-bold text-gray-900 mb-3">
                    {{ t("contact_title") }}
                </h1>
                <p class="text-gray-500 text-lg mb-10">
                    {{ t("contact_desc") }}
                </p>
            </div>
            <div class="grid gap-5 mb-12">
                <a
                    v-for="(item, i) in contacts"
                    :key="item.label"
                    :href="item.href"
                    :target="item.target"
                    v-motion
                    :initial="{ opacity: 0, x: i % 2 === 0 ? -40 : 40 }"
                    :enter="{
                        opacity: 1,
                        x: 0,
                        transition: { duration: 500, delay: i * 100 },
                    }"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center gap-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-right"
                >
                    <div
                        class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0"
                        :class="item.bg"
                    >
                        {{ item.icon }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-900 text-lg">
                            {{ item.label }}
                        </div>
                        <div class="text-gray-500 text-sm">{{ item.desc }}</div>
                    </div>
                    <div class="mr-auto text-gray-300 text-xl">←</div>
                </a>
            </div>
            <div
                v-motion
                :initial="{ opacity: 0, scale: 0.9 }"
                :visible="{
                    opacity: 1,
                    scale: 1,
                    transition: { duration: 500 },
                }"
                class="bg-blue-50 border border-blue-100 rounded-2xl p-8"
            >
                <p class="text-gray-600 mb-4">{{ t("contact_no_idea") }}</p>
                <RouterLink
                    to="/start"
                    class="bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200 inline-block"
                >
                    {{ t("contact_cta") }}
                </RouterLink>
            </div>
        </div>
    </div>
</template>
<script setup>
import { useLang } from "@/i18n";
const { t } = useLang();
const contacts = [
    {
        label: "WhatsApp",
        desc: "هنرد عليكِ خلال ساعات",
        icon: "📱",
        bg: "bg-green-100",
        href: "https://wa.me/message/G4I5UY6LPA4NE1",
        target: "_blank",
    },
    {
        label: "Email",
        desc: "hello@germantime.com",
        icon: "📧",
        bg: "bg-blue-100",
        href: "mailto:hello@germantime.com",
        target: "_self",
    },
    {
        label: "Instagram",
        desc: "@germantime",
        icon: "📸",
        bg: "bg-pink-100",
        href: "https://www.instagram.com/germantime_edu?igsh=ZTNkdjg4ZjczeHZk&utm_source=qr",
        target: "_blank",
    },
    {
        label: "TikTok",
        desc: "@germantime",
        icon: "🎵",
        bg: "bg-gray-100",
        href: "https://www.tiktok.com/@germantime_edu?_r=1&_t=ZS-96IZwizoI0D",
        target: "_blank",
    },
];
</script>
