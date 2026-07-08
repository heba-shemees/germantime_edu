<template>
    <div>
        <!-- HERO -->
        <section
            class="relative bg-gradient-to-br from-blue-50 via-white to-blue-50 pt-20 pb-24 px-4 overflow-hidden"
        >
            <div
                class="absolute top-10 right-10 w-64 h-64 bg-blue-100 rounded-full opacity-20 blur-3xl animate-pulse"
            ></div>
            <div
                class="absolute bottom-10 left-10 w-48 h-48 bg-indigo-100 rounded-full opacity-30 blur-2xl animate-pulse"
                style="animation-delay: 1s"
            ></div>

            <div
                class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center relative"
            >
                <!-- Text -->
                <div
                    class="text-center md:text-right order-2 md:order-1"
                    v-motion
                    :initial="{ opacity: 0, y: 40 }"
                    :enter="{ opacity: 1, y: 0, transition: { duration: 700 } }"
                >
                    <div
                        class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium mb-6"
                    >
                        ✨ {{ t("hero_badge") }}
                    </div>
                    <h1
                        class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight mb-6"
                    >
                        {{ t("hero_title_1") }}<br />
                        <span class="text-blue-600">{{
                            t("hero_title_2")
                        }}</span>
                    </h1>
                    <p class="text-gray-500 text-lg leading-relaxed mb-8">
                        {{ t("hero_desc") }}
                    </p>
                    <div
                        class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start"
                    >
                        <RouterLink
                            to="/start"
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200 text-center shadow-lg shadow-blue-200"
                        >
                            {{ t("hero_cta") }}
                        </RouterLink>
                        <RouterLink
                            to="/courses"
                            class="border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-blue-50 hover:scale-105 active:scale-95 transition-all duration-200 text-center"
                        >
                            {{ t("hero_courses") }}
                        </RouterLink>
                    </div>
                </div>

                <!-- Teacher image -->
                <div
                    class="order-1 md:order-2 flex justify-center"
                    v-motion
                    :initial="{ opacity: 0, scale: 0.8 }"
                    :enter="{
                        opacity: 1,
                        scale: 1,
                        transition: { duration: 600, delay: 200 },
                    }"
                >
                    <div class="relative">
                        <div
                            class="w-72 h-72 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300"
                        >
                            <img
                                :src="teacherImage"
                                alt="فراو هبه"
                                class="w-full h-full object-cover object-top"
                            />
                        </div>
                        <div
                            class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-md px-4 py-2 text-sm font-semibold text-blue-700 border border-blue-100 animate-bounce"
                        >
                            A1 → B2
                        </div>
                        <div
                            class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-md px-4 py-2 text-sm font-semibold text-green-600 border border-green-100"
                        >
                            ⭐ 4.9
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STATS -->
        <section class="py-12 bg-white border-y border-gray-100">
            <div class="max-w-4xl mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div
                        v-for="(stat, i) in stats"
                        :key="stat.labelKey"
                        v-motion
                        :initial="{ opacity: 0, y: 20 }"
                        :enter="{
                            opacity: 1,
                            y: 0,
                            transition: { duration: 500, delay: i * 100 },
                        }"
                    >
                        <div class="text-3xl font-bold text-blue-700">
                            {{ stat.value }}
                        </div>
                        <div class="text-sm text-gray-500 mt-1">
                            {{ t(stat.labelKey) }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- COURSES -->
        <section class="py-20 px-4 bg-gray-50">
            <div class="max-w-6xl mx-auto">
                <div
                    class="text-center mb-12"
                    v-motion
                    :initial="{ opacity: 0, y: 30 }"
                    :visible="{
                        opacity: 1,
                        y: 0,
                        transition: { duration: 600 },
                    }"
                >
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">
                        {{ t("courses_title") }}
                    </h2>
                    <p class="text-gray-500 text-lg">
                        {{ t("courses_subtitle") }}
                    </p>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="(course, i) in courses"
                        :key="course.level"
                        v-motion
                        :initial="{ opacity: 0, y: 40 }"
                        :visible="{
                            opacity: 1,
                            y: 0,
                            transition: { duration: 500, delay: i * 100 },
                        }"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 p-6 flex flex-col gap-4"
                    >
                        <div class="flex items-center justify-between">
                            <span
                                class="px-3 py-1 rounded-full text-sm font-semibold"
                                :class="badgeClass(course.level)"
                                >{{ course.level }}</span
                            >
                            <span class="text-xs text-gray-400">{{
                                course.duration[lang]
                            }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">
                                {{ course.name[lang] }}
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">
                                {{ course.description[lang] }}
                            </p>
                        </div>
                        <div class="mt-auto">
                            <div class="text-2xl font-bold text-gray-900 mb-3">
                                {{ course.price }}
                                <span
                                    class="text-sm font-normal text-gray-400"
                                    >{{ t("currency") }}</span
                                >
                            </div>
                            <RouterLink
                                :to="`/courses/${course.id}`"
                                class="bg-blue-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200 block text-center text-sm"
                            >
                                {{ t("course_details") }}
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TESTIMONIALS -->
        <section class="py-20 px-4 bg-white">
            <div class="max-w-6xl mx-auto">
                <div
                    class="text-center mb-12"
                    v-motion
                    :initial="{ opacity: 0, y: 30 }"
                    :visible="{
                        opacity: 1,
                        y: 0,
                        transition: { duration: 600 },
                    }"
                >
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">
                        {{ t("testimonials_title") }}
                    </h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        v-for="(item, i) in testimonials"
                        :key="item.name"
                        v-motion
                        :initial="{ opacity: 0, x: i % 2 === 0 ? -30 : 30 }"
                        :visible="{
                            opacity: 1,
                            x: 0,
                            transition: { duration: 500, delay: i * 150 },
                        }"
                        class="bg-gray-50 rounded-2xl border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300"
                    >
                        <div
                            class="flex items-center gap-1 text-yellow-400 mb-3"
                        >
                            ⭐⭐⭐⭐⭐
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed mb-4">
                            "{{ item.text[lang] }}"
                        </p>
                        <div class="font-semibold text-sm">{{ item.name }}</div>
                        <div class="text-xs text-gray-400">
                            {{ item.level }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section
            class="py-20 px-4 bg-blue-700 text-white text-center relative overflow-hidden"
        >
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute top-0 left-1/4 w-96 h-96 bg-white rounded-full blur-3xl"
                ></div>
                <div
                    class="absolute bottom-0 right-1/4 w-64 h-64 bg-blue-300 rounded-full blur-2xl"
                ></div>
            </div>
            <div
                class="max-w-2xl mx-auto relative"
                v-motion
                :initial="{ opacity: 0, scale: 0.9 }"
                :visible="{
                    opacity: 1,
                    scale: 1,
                    transition: { duration: 600 },
                }"
            >
                <h2 class="text-3xl font-bold mb-4">{{ t("cta_title") }}</h2>
                <p class="text-blue-200 mb-8 text-lg">{{ t("cta_desc") }}</p>
                <RouterLink
                    to="/start"
                    class="inline-block bg-white text-blue-700 font-bold px-8 py-4 rounded-2xl text-lg hover:bg-blue-50 hover:scale-105 active:scale-95 transition-all duration-200 shadow-xl"
                >
                    {{ t("cta_btn") }}
                </RouterLink>
            </div>
        </section>
    </div>
</template>

<script setup>
import { useLang } from "@/i18n";

const { lang, t } = useLang();

const teacherImage = "/images/teacher.jpg";

const stats = [
    { value: "200+", labelKey: "stat_students" },
    { value: "4", labelKey: "stat_levels" },
    { value: "98%", labelKey: "stat_satisfaction" },
    { value: "3+", labelKey: "stat_experience" },
];

const courses = [
    {
        id: 1,
        level: "A1",
        name: { ar: "المبتدئ", de: "Anfänger" },
        duration: { ar: "8 أسابيع", de: "8 Wochen" },
        price: 800,
        description: {
            ar: "ابدئي من الصفر — حروف، أرقام، جمل أساسية",
            de: "Von Null anfangen — Buchstaben, Zahlen, einfache Sätze",
        },
    },
    {
        id: 2,
        level: "A2",
        name: { ar: "الأساسي", de: "Grundstufe" },
        duration: { ar: "8 أسابيع", de: "8 Wochen" },
        price: 900,
        description: {
            ar: "محادثة يومية وتوسيع المفردات",
            de: "Alltagsgespräche und Wortschatzerweiterung",
        },
    },
    {
        id: 3,
        level: "B1",
        name: { ar: "المتوسط", de: "Mittelstufe" },
        duration: { ar: "10 أسابيع", de: "10 Wochen" },
        price: 1100,
        description: {
            ar: "قواعد متقدمة وفهم النصوص",
            de: "Fortgeschrittene Grammatik und Textverständnis",
        },
    },
    {
        id: 4,
        level: "B2",
        name: { ar: "المتقدم", de: "Oberstufe" },
        duration: { ar: "12 أسابيع", de: "12 Wochen" },
        price: 1300,
        description: {
            ar: "طلاقة في المحادثة والكتابة",
            de: "Fließendes Sprechen und Schreiben",
        },
    },
];

const testimonials = [
    {
        name: "سارة أحمد",
        level: "A2",
        text: {
            ar: "الأسلوب بسيط ومفهوم جداً، بدأت من الصفر وبقيت قادرة على التكلم في 3 أشهر",
            de: "Die Methode ist sehr einfach. Ich habe von Null angefangen und kann nach 3 Monaten sprechen.",
        },
    },
    {
        name: "مريم علي",
        level: "B1",
        text: {
            ar: "نور شرحت القواعد الصعبة بطريقة ما توقعتيش إنها تبقى سهلة الفهم",
            de: "Nour hat die schwierige Grammatik auf eine Weise erklärt, die ich nie erwartet hätte.",
        },
    },
    {
        name: "دينا محمد",
        level: "A1",
        text: {
            ar: "كنت خايفة من الألماني وبعد الكورس اتغيرت نظرتي خالص",
            de: "Ich hatte Angst vor Deutsch, aber nach dem Kurs hat sich meine Sichtweise völlig verändert.",
        },
    },
];

function badgeClass(level) {
    return {
        A1: "bg-green-100 text-green-800",
        A2: "bg-blue-100 text-blue-800",
        B1: "bg-purple-100 text-purple-800",
        B2: "bg-orange-100 text-orange-800",
    }[level];
}
</script>
