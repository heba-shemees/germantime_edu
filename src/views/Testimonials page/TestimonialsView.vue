<template>
    <div class="py-16 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="section-title">آراء الطلبة</h1>
                <p class="section-subtitle">
                    تجارب حقيقية من طالبات عملوا كورسات معنا
                </p>
            </div>

            <!-- Filter by level -->
            <div class="flex justify-center gap-3 mb-10 flex-wrap">
                <button
                    v-for="f in filters"
                    :key="f"
                    class="px-4 py-2 rounded-full text-sm font-semibold border-2 transition-all"
                    :class="
                        active === f
                            ? 'bg-brand-600 border-brand-600 text-white'
                            : 'border-gray-200 text-gray-600 hover:border-brand-300'
                    "
                    @click="active = f"
                >
                    {{ f }}
                </button>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="t in filtered"
                    :key="t.name"
                    class="card p-6 flex flex-col gap-4"
                >
                    <div class="flex items-center gap-1 text-yellow-400">
                        <span v-for="i in 5" :key="i">⭐</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed flex-1">
                        "{{ t.text }}"
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center font-bold text-brand-700 text-sm"
                            >
                                {{ t.name.charAt(0) }}
                            </div>
                            <div>
                                <div
                                    class="font-semibold text-sm text-gray-900"
                                >
                                    {{ t.name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ t.location }}
                                </div>
                            </div>
                        </div>
                        <span
                            class="level-badge text-xs"
                            :class="levelBadge(t.level)"
                            >{{ t.level }}</span
                        >
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center mt-14">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    انضم الأن
                </h2>
                <RouterLink to="/start" class="btn-primary text-lg px-10 py-4">
                    أبدأ اختبار المستوى ←
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const active = ref("الكل");
const filters = ["الكل", "A1", "A2", "B1", "B2"];

const testimonials = [
    {
        name: "سارة أحمد",
        level: "A1",
        location: "القاهرة",
        text: "بدأت من الصفر وبعد كورس A1 بقيت أقدر أتعرف على ناس ألمان وأفهم إيه اللي بيقولوه!",
    },
    {
        name: "مريم خالد",
        level: "A2",
        location: "الإسكندرية",
        text: "كنت خايفة من اللغة الألمانية جداً بس نور غيرت نظرتي خالص، الشرح بسيط ومبهج.",
    },
    {
        name: "دينا يوسف",
        level: "B1",
        location: "الغردقة",
        text: "وصلت B1 وبقيت أفهم الفيديوهات الألمانية بدون ترجمة — حاجة كنت أحلم بيها!",
    },
    {
        name: "هنا سامي",
        level: "A1",
        location: "القاهرة",
        text: "أسلوب التدريس مختلف تماماً عن أي كورس اتعلمته قبل كده. عملي جداً.",
    },
    {
        name: "نورهان علي",
        level: "B2",
        location: "المنصورة",
        text: "اجتزت اختبار Goethe B2 من أول مرة! مش كنت هقدر من غير كورسات نور.",
    },
    {
        name: "ريم محمود",
        level: "A2",
        location: "الجيزة",
        text: "الواجبات والمراجعات ساعدتني أثبت اللغة. محتاج وقت بس النتيجة تستاهل.",
    },
];

const filtered = computed(() =>
    active.value === "الكل"
        ? testimonials
        : testimonials.filter((t) => t.level === active.value)
);

function levelBadge(level) {
    return {
        A1: "bg-green-100 text-green-800",
        A2: "bg-blue-100 text-blue-800",
        B1: "bg-purple-100 text-purple-800",
        B2: "bg-orange-100 text-orange-800",
    }[level];
}
</script>
