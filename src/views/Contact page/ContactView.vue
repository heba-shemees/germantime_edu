<template>
    <div class="py-16 px-4">
        <div class="max-w-2xl mx-auto text-center">
            <h1 class="section-title">تواصل معنا</h1>
            <p class="section-subtitle">هنرد عليك في أسرع وقت ممكن</p>

            <div class="grid gap-5 mb-12">
                <a
                    :href="whatsappLink"
                    target="_blank"
                    class="card p-6 flex items-center gap-5 hover:-translate-y-1 transition-transform text-right"
                >
                    <div
                        class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-3xl flex-shrink-0"
                    >
                        📱
                    </div>
                    <div>
                        <div class="font-bold text-gray-900 text-lg">
                            واتساب
                        </div>
                        <div class="text-gray-500 text-sm">
                            الأسرع — هنرد عليك خلال ساعات
                        </div>
                        <div class="text-brand-600 font-mono text-sm mt-1">
                            +20 100 000 0000
                        </div>
                    </div>
                    <div class="mr-auto text-gray-300 text-xl">←</div>
                </a>

                <a
                    href="mailto:hello@germantime.com"
                    class="card p-6 flex items-center gap-5 hover:-translate-y-1 transition-transform text-right"
                >
                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-3xl flex-shrink-0"
                    >
                        📧
                    </div>
                    <div>
                        <div class="font-bold text-gray-900 text-lg">إيميل</div>
                        <div class="text-gray-500 text-sm">
                            للاستفسارات الرسمية
                        </div>
                        <div class="text-brand-600 text-sm mt-1">
                            hello@germantime.com
                        </div>
                    </div>
                    <div class="mr-auto text-gray-300 text-xl">←</div>
                </a>

                <a
                    href="https://instagram.com"
                    target="_blank"
                    class="card p-6 flex items-center gap-5 hover:-translate-y-1 transition-transform text-right"
                >
                    <div
                        class="w-14 h-14 rounded-2xl bg-pink-100 flex items-center justify-center text-3xl flex-shrink-0"
                    >
                        📸
                    </div>
                    <div>
                        <div class="font-bold text-gray-900 text-lg">
                            إنستجرام
                        </div>
                        <div class="text-gray-500 text-sm">
                            محتوى يومي وتيبس مجانية
                        </div>
                        <div class="text-brand-600 text-sm mt-1">
                            @germantime
                        </div>
                    </div>
                    <div class="mr-auto text-gray-300 text-xl">←</div>
                </a>

                <a
                    href="https://tiktok.com"
                    target="_blank"
                    class="card p-6 flex items-center gap-5 hover:-translate-y-1 transition-transform text-right"
                >
                    <div
                        class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-3xl flex-shrink-0"
                    >
                        🎵
                    </div>
                    <div>
                        <div class="font-bold text-gray-900 text-lg">
                            تيك توك
                        </div>
                        <div class="text-gray-500 text-sm">
                            فيديوهات تعليمية قصيرة
                        </div>
                        <div class="text-brand-600 text-sm mt-1">
                            @germantime
                        </div>
                    </div>
                    <div class="mr-auto text-gray-300 text-xl">←</div>
                </a>
            </div>

            <div class="card p-8 bg-brand-50 border-brand-100">
                <p class="text-gray-600 mb-4">مش عارفة تبدئي من فين؟</p>
                <RouterLink to="/start" class="btn-primary">
                    اعمل اختبار مستوى مجاني ←
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
const WHATSAPP_NUMBER = "201000000000";
const whatsappLink = `https://wa.me/${WHATSAPP_NUMBER}`;
</script>
