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
                    v-for="(item, i) in contacts" :key="item.label"
                    :href="item.href" :target="item.target" v-motion :initial="{
                    opacity: 0, x: i % 2 === 0 ? -40 : 40 }" :enter="{ opacity:
                    1, x: 0, transition: { duration: 500, delay: i * 100 }, }"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm
                    p-6 flex items-center gap-5 hover:shadow-lg
                    hover:-translate-y-1 transition-all duration-300 text-right"
                    >
                    <div
                        class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shrink-0"
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

            <!-- فورم التواصل -->
            <div
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :visible="{ opacity: 1, y: 0, transition: { duration: 500 } }"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-12 text-right"
            >
                <h2 class="font-bold text-xl text-gray-900 mb-1 text-center">
                    عندك استفسار؟ ابعتلنا رسالة
                </h2>
                <p class="text-gray-500 text-sm mb-6 text-center">
                    هنرد عليكِ في أسرع وقت ممكن
                </p>

                <div v-if="submitted" class="text-center py-6">
                    <div class="text-5xl mb-3">✅</div>
                    <p class="text-green-600 font-semibold">
                        تم إرسال رسالتك بنجاح، هنتواصل معاكِ قريبًا
                    </p>
                    <button
                        @click="resetForm"
                        class="mt-4 text-blue-600 text-sm font-semibold hover:underline"
                    >
                        إرسال رسالة أخرى
                    </button>
                </div>

                <div v-else class="space-y-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-600 mb-1"
                        >
                            الاسم *
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="اسمك"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-600 mb-1"
                            >
                                الإيميل
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                placeholder="example@email.com"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-600 mb-1"
                            >
                                رقم الهاتف / واتساب
                            </label>
                            <input
                                v-model="form.phone"
                                type="text"
                                class="w-full border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                placeholder="01xxxxxxxxx"
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-600 mb-1"
                        >
                            رسالتك *
                        </label>
                        <textarea
                            v-model="form.message"
                            rows="4"
                            class="w-full border border-gray-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="اكتبي استفسارك هنا..."
                        ></textarea>
                    </div>

                    <p v-if="formError" class="text-red-500 text-sm">
                        {{ formError }}
                    </p>

                    <button
                        @click="submitForm"
                        :disabled="sending"
                        class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 hover:scale-[1.02] active:scale-95 transition-all duration-200 disabled:opacity-50"
                    >
                        {{ sending ? "جاري الإرسال..." : "إرسال الرسالة" }}
                    </button>
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
import { ref } from "vue";
import axios from "axios";
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

// ── فورم التواصل ──
const emptyForm = () => ({ name: "", email: "", phone: "", message: "" });
const form = ref(emptyForm());
const sending = ref(false);
const submitted = ref(false);
const formError = ref("");

async function submitForm() {
    if (!form.value.name || !form.value.message) {
        formError.value = "برجاء كتابة الاسم والرسالة على الأقل";
        return;
    }
    sending.value = true;
    formError.value = "";
    try {
        await axios.post("/api/v1/contact", form.value);
        submitted.value = true;
    } catch (err) {
        formError.value =
            err?.response?.data?.message || "حصل خطأ أثناء الإرسال، حاولي تاني";
    } finally {
        sending.value = false;
    }
}

function resetForm() {
    form.value = emptyForm();
    submitted.value = false;
    formError.value = "";
}
</script>
