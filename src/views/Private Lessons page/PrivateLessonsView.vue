<template>
    <div class="py-16 px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Hero -->
            <div class="text-center mb-12">
                <div class="text-5xl mb-4">🎯</div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    الحصص الخاصة
                </h1>
                <p
                    class="text-gray-500 text-lg max-w-xl mx-auto leading-relaxed"
                >
                    اهتمام كامل بيكِ — مناهج مخصصة لمستواك وأهدافك بالضبط
                </p>
            </div>

            <!-- Benefits -->
            <div class="grid md:grid-cols-3 gap-5 mb-12">
                <div
                    v-for="b in benefits"
                    :key="b.title"
                    class="card p-6 text-center"
                >
                    <div class="text-3xl mb-3">{{ b.icon }}</div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ b.title }}</h3>
                    <p class="text-sm text-gray-500">{{ b.desc }}</p>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card p-8 mb-10">
                <h2 class="text-xl font-bold text-gray-900 mb-6 text-center">
                    الأسعار
                </h2>
                <div class="space-y-4">
                    <div
                        v-for="pkg in packages"
                        :key="pkg.name"
                        class="flex items-center justify-between p-5 rounded-2xl border-2 transition-all cursor-pointer"
                        :class="
                            selected === pkg.name
                                ? 'border-brand-500 bg-brand-50'
                                : 'border-gray-100 hover:border-brand-200'
                        "
                        @click="selected = pkg.name"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                :class="
                                    selected === pkg.name
                                        ? 'border-brand-500 bg-brand-500'
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
                            <div class="font-bold text-xl text-brand-700">
                                {{ pkg.price }}
                            </div>
                            <div class="text-xs text-gray-400">جنيه</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center">
                <RouterLink
                    :to="`/booking?level=private`"
                    class="btn-primary text-lg px-12 py-4"
                >
                    احجز حصة خاصة ←
                </RouterLink>
                <p class="text-xs text-gray-400 mt-4">
                    هنتواصل معاك خلال 24 ساعة لتأكيد الموعد
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

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
