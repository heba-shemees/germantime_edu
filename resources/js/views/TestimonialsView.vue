<template>
    <div class="py-16 px-4 overflow-hidden">
        <div class="max-w-5xl mx-auto">
            <div
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :enter="{ opacity: 1, y: 0, transition: { duration: 600 } }"
                class="text-center mb-12"
            >
                <h1 class="text-3xl font-bold text-gray-900 mb-3">
                    {{ t("testimonials_title") }}
                </h1>
                <p class="text-gray-500 text-lg mb-10">
                    {{ t("testimonials_subtitle") }}
                </p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="(item, i) in testimonials"
                    :key="item.name"
                    v-motion
                    :initial="{ opacity: 0, y: 40 }"
                    :visible="{
                        opacity: 1,
                        y: 0,
                        transition: { duration: 500, delay: i * 100 },
                    }"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 p-6"
                >
                    <div class="flex gap-1 text-yellow-400 mb-3">
                        ⭐⭐⭐⭐⭐
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        "{{ item.text[lang] }}"
                    </p>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-sm"
                        >
                            {{ item.name.charAt(0) }}
                        </div>
                        <div>
                            <div class="font-semibold text-sm">
                                {{ item.name }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ item.level }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :visible="{ opacity: 1, y: 0, transition: { duration: 600 } }"
                class="text-center mt-14"
            >
                <RouterLink
                    to="/start"
                    class="bg-blue-600 text-white px-10 py-4 rounded-xl font-bold text-lg hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200 inline-block shadow-lg shadow-blue-200"
                >
                    {{ t("cta_btn") }}
                </RouterLink>
            </div>
        </div>
    </div>
</template>
<script setup>
import { useLang } from "@/i18n";
const { lang, t } = useLang();
const testimonials = [
    {
        name: "سارة أحمد",
        level: "A1",
        text: {
            ar: "بدأت من الصفر وبعد 8 أسابيع بقيت أقدر أتكلم جمل كاملة.",
            de: "Ich habe von Null angefangen und kann nach 8 Wochen ganze Sätze sprechen.",
        },
    },
    {
        name: "مريم خالد",
        level: "A2",
        text: {
            ar: "كنت خايفة من اللغة الألمانية بس نور غيرت نظرتي خالص.",
            de: "Ich hatte Angst vor Deutsch, aber Nour hat meine Sichtweise völlig verändert.",
        },
    },
    {
        name: "دينا يوسف",
        level: "B1",
        text: {
            ar: "وصلت B1 وبقيت أفهم الفيديوهات الألمانية بدون ترجمة!",
            de: "Ich habe B1 erreicht und verstehe deutsche Videos ohne Untertitel!",
        },
    },
    {
        name: "هنا سامي",
        level: "A1",
        text: {
            ar: "أسلوب التدريس مختلف تماماً عن أي كورس اتعلمته قبل كده.",
            de: "Der Unterrichtsstil ist völlig anders als jeder Kurs, den ich je besucht habe.",
        },
    },
    {
        name: "نورهان علي",
        level: "B2",
        text: {
            ar: "اجتزت اختبار Goethe B2 من أول مرة!",
            de: "Ich habe die Goethe B2-Prüfung beim ersten Versuch bestanden!",
        },
    },
    {
        name: "ريم محمود",
        level: "A2",
        text: {
            ar: "الواجبات والمراجعات ساعدتني أثبت اللغة.",
            de: "Die Hausaufgaben und Wiederholungen haben mir geholfen, die Sprache zu festigen.",
        },
    },
];
</script>
