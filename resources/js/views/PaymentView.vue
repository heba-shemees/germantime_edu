<template>
    <div class="py-16 px-4 bg-gray-50 min-h-screen overflow-hidden">
        <div class="max-w-xl mx-auto">
            <div
                v-motion
                :initial="{ opacity: 0, scale: 0.5 }"
                :enter="{
                    opacity: 1,
                    scale: 1,
                    transition: { duration: 600, type: 'spring' },
                }"
                class="text-center mb-10"
            >
                <div class="text-5xl mb-4">✅</div>
                <h1 class="text-3xl font-bold text-gray-900 mb-3">
                    {{ t("payment_title") }}
                </h1>
                <p class="text-gray-500">{{ t("payment_desc") }}</p>
            </div>

            <!-- طرق الدفع -->
            <div class="space-y-4 mb-8">
                <div
                    v-for="(method, i) in methods"
                    :key="method.label"
                    v-motion
                    :initial="{ opacity: 0, x: i === 0 ? -40 : 40 }"
                    :enter="{
                        opacity: 1,
                        x: 0,
                        transition: { duration: 500, delay: i * 150 },
                    }"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                >
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center text-xl"
                            :class="method.bg"
                        >
                            {{ method.icon }}
                        </div>
                        <h3 class="font-semibold text-gray-900">
                            {{ method.label }}
                        </h3>
                    </div>
                    <div
                        class="bg-gray-50 rounded-xl px-4 py-3 flex items-center justify-between"
                    >
                        <span class="font-mono font-bold text-lg">{{
                            method.number
                        }}</span>
                        <button
                            @click="copyText(method.number)"
                            class="text-blue-600 text-sm font-medium hover:underline"
                        >
                            {{
                                copied === method.number
                                    ? t("copied")
                                    : t("copy")
                            }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- تعليمات -->
            <div
                v-motion
                :initial="{ opacity: 0, y: 20 }"
                :enter="{
                    opacity: 1,
                    y: 0,
                    transition: { duration: 500, delay: 400 },
                }"
                class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-6"
            >
                <h3 class="font-semibold text-yellow-800 mb-3">
                    {{ t("payment_warning") }}
                </h3>
                <ol
                    class="space-y-2 text-sm text-yellow-900 list-decimal list-inside"
                >
                    <li>{{ t("payment_step1") }}</li>
                    <li>{{ t("payment_step2") }}</li>
                    <li>{{ t("payment_step3") }}</li>
                </ol>
            </div>

            <!-- مؤقت 24 ساعة -->
            <div
                class="bg-orange-50 border border-orange-200 rounded-2xl p-4 mb-6 text-center"
            >
                <p class="text-sm font-semibold text-orange-700">
                    ⏳ الحجز معلق لمدة
                </p>
                <p class="text-2xl font-bold text-orange-600 mt-1">
                    {{ countdown }}
                </p>
                <p class="text-xs text-orange-500 mt-1">
                    بعدها يتلغى الحجز تلقائياً لو مرفعتش الإيصال
                </p>
            </div>

            <!-- رفع الإيصال -->
            <div
                v-motion
                :initial="{ opacity: 0, y: 20 }"
                :enter="{
                    opacity: 1,
                    y: 0,
                    transition: { duration: 500, delay: 500 },
                }"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6"
            >
                <h3 class="font-semibold text-gray-900 mb-4">
                    📎 ارفع صورة الإيصال
                </h3>

                <!-- تم الرفع -->
                <div v-if="uploadDone" class="text-center py-6">
                    <div class="text-4xl mb-3">🎉</div>
                    <p class="font-semibold text-green-600">
                        تم رفع الإيصال بنجاح!
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        هنراجعه ونأكدلك الحجز على الواتساب خلال 24 ساعة
                    </p>
                </div>

                <!-- فورم الرفع -->
                <div v-else>
                    <label
                        class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed rounded-xl cursor-pointer transition-all"
                        :class="
                            receiptFile
                                ? 'border-blue-400 bg-blue-50'
                                : 'border-gray-300 hover:border-blue-300 bg-gray-50'
                        "
                    >
                        <input
                            type="file"
                            accept="image/*"
                            class="hidden"
                            @change="onFileChange"
                        />
                        <div v-if="receiptFile" class="text-center">
                            <div class="text-3xl mb-1">🖼️</div>
                            <p class="text-sm font-medium text-blue-700">
                                {{ receiptFile.name }}
                            </p>
                            <p class="text-xs text-gray-400">
                                اضغط لتغيير الصورة
                            </p>
                        </div>
                        <div v-else class="text-center">
                            <div class="text-3xl mb-1">📷</div>
                            <p class="text-sm text-gray-500">
                                اضغط لاختيار صورة الإيصال
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                JPG, PNG — حتى 5MB
                            </p>
                        </div>
                    </label>

                    <p
                        v-if="uploadError"
                        class="text-red-500 text-sm text-center mt-3"
                    >
                        {{ uploadError }}
                    </p>

                    <button
                        @click="uploadReceipt"
                        :disabled="!receiptFile || uploading"
                        class="mt-4 w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <span v-if="uploading">جاري الرفع...</span>
                        <span v-else>ارفع الإيصال ✓</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useLang } from "@/i18n";
import axios from "axios";

const { t } = useLang();

const methods = [
    {
        label: "Vodafone Cash",
        icon: "📱",
        bg: "bg-red-50",
        number: "01000000000",
    },
    { label: "InstaPay", icon: "🏦", bg: "bg-blue-50", number: "yourinstapay" },
];

const booking = JSON.parse(sessionStorage.getItem("booking") || "{}");
const copied = ref(null);
const receiptFile = ref(null);
const uploading = ref(false);
const uploadDone = ref(false);
const uploadError = ref("");
const countdown = ref("");

let timer;
function startCountdown() {
    const savedExpiry = sessionStorage.getItem("booking_expiry");
    const expiry = savedExpiry
        ? new Date(savedExpiry)
        : new Date(Date.now() + 24 * 60 * 60 * 1000);

    if (!savedExpiry) {
        sessionStorage.setItem("booking_expiry", expiry.toISOString());
    }

    timer = setInterval(() => {
        const diff = expiry - Date.now();
        if (diff <= 0) {
            countdown.value = "انتهى الوقت";
            clearInterval(timer);
            return;
        }
        const h = String(Math.floor(diff / 3600000)).padStart(2, "0");
        const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, "0");
        const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, "0");
        countdown.value = `${h}:${m}:${s}`;
    }, 1000);
}

function copyText(val) {
    navigator.clipboard.writeText(val);
    copied.value = val;
    setTimeout(() => (copied.value = null), 2000);
}

function onFileChange(e) {
    receiptFile.value = e.target.files[0] || null;
    uploadError.value = "";
}

async function uploadReceipt() {
    if (!receiptFile.value) return;

    if (!booking.id) {
        uploadError.value = "حصل خطأ في بيانات الحجز، برجاء إعادة الحجز";
        return;
    }

    uploading.value = true;
    uploadError.value = "";

    const formData = new FormData();
    formData.append("receipt", receiptFile.value);

    try {
        await axios.post(`/api/v1/bookings/${booking.id}/receipt`, formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        uploadDone.value = true;
        clearInterval(timer);
    } catch (err) {
        uploadError.value =
            err?.response?.data?.message || "حصل خطأ، حاول تاني";
    } finally {
        uploading.value = false;
    }
}

onMounted(startCountdown);
onUnmounted(() => clearInterval(timer));
</script>
