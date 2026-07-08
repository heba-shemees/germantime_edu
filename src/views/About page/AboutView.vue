<template>
    <div class="py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Hero -->
            <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
                <div class="flex justify-center">
                    <div
                        class="w-64 h-64 rounded-3xl bg-brand-100 flex items-center justify-center text-8xl shadow-lg"
                    >
                        👩‍🏫
                    </div>
                </div>
                <div>
                    <div
                        class="inline-flex items-center gap-2 bg-brand-100 text-brand-700 px-4 py-2 rounded-full text-sm font-medium mb-4"
                    >
                        ✨ معلمة ألماني متخصصة
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        أنا فراو هبه
                    </h1>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        معلمة لغة ألمانية شغوفة بتعليم اللغات بطريقة سهلة
                        وعملية. هدفي إن كل طالبة تحس إن الألماني مش صعب — بس
                        محتاج الطريقة الصح.
                    </p>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-16">
                <div
                    v-for="s in stats"
                    :key="s.label"
                    class="card p-6 text-center"
                >
                    <div class="text-3xl font-extrabold text-brand-700 mb-1">
                        {{ s.value }}
                    </div>
                    <div class="text-sm text-gray-500">{{ s.label }}</div>
                </div>
            </div>

            <!-- Experience -->
            <div class="card p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">خبرتي</h2>
                <div class="space-y-5">
                    <div
                        v-for="exp in experience"
                        :key="exp.title"
                        class="flex gap-4"
                    >
                        <div
                            class="w-10 h-10 rounded-xl bg-brand-100 flex items-center justify-center text-lg flex-shrink-0"
                        >
                            {{ exp.icon }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">
                                {{ exp.title }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-0.5">
                                {{ exp.desc }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teaching style -->
            <div class="card p-8 mb-8 bg-brand-50 border-brand-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    أسلوب التدريس
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div
                        v-for="item in teachingStyle"
                        :key="item.title"
                        class="flex gap-3 items-start"
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

            <!-- CTA -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">مستنياكي!</h2>
                <p class="text-gray-500 mb-6">ابدئي رحلتك مع الألماني دلوقتي</p>
                <RouterLink to="/start" class="btn-primary text-lg px-10 py-4">
                    أبدأ اختبار المستوى ←
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
const stats = [
    { value: "200+", label: "طالبة" },
    { value: "3+", label: "سنوات خبرة" },
    { value: "A1–B2", label: "كل المستويات" },
    { value: "98%", label: "نسبة رضا" },
];

const experience = [
    {
        icon: "🎓",
        title: "شهادة تدريس اللغة الألمانية",
        desc: "متخصصة في تعليم اللغة الألمانية للناطقين بالعربية",
    },
    {
        icon: "🇩🇪",
        title: "خبرة في ألمانيا",
        desc: "عشت ودرست في ألمانيا لأكثر من سنتين",
    },
    {
        icon: "💻",
        title: "تدريس أونلاين منذ 2021",
        desc: "أكثر من 200 طالبة من مصر والدول العربية",
    },
    {
        icon: "📜",
        title: "شهادة Goethe Institut",
        desc: "مؤهلة لتدريس مستويات A1 حتى B2",
    },
];

const teachingStyle = [
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
    { icon: "📝", title: "واجبات مخصصة", desc: "مواد مناسبة لمستواكي بالضبط" },
    {
        icon: "💬",
        title: "دعم خارج الحصص",
        desc: "تقدري تسأليني على واتساب في أي وقت",
    },
];
</script>
