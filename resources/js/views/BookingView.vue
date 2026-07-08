<template>
    <div class="py-16 px-4 bg-gray-50 min-h-screen overflow-hidden">
        <div class="max-w-xl mx-auto">
            <div
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :enter="{ opacity: 1, y: 0, transition: { duration: 600 } }"
                class="text-center mb-10"
            >
                <h1 class="text-3xl font-bold text-gray-900 mb-3">
                    {{ t("booking_title") }}
                </h1>
                <p class="text-gray-500">{{ t("booking_desc") }}</p>
            </div>

            <div
                v-motion
                :initial="{ opacity: 0, y: 40 }"
                :enter="{
                    opacity: 1,
                    y: 0,
                    transition: { duration: 600, delay: 200 },
                }"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8"
            >
                <!-- معلومات الموعد المختار -->
                <div
                    v-if="selectedScheduleInfo"
                    class="mb-6 bg-blue-50 border border-blue-100 rounded-xl p-4"
                >
                    <p class="text-sm font-semibold text-blue-800 mb-1">
                        📅 الموعد المختار:
                    </p>
                    <p class="text-sm text-blue-700">
                        {{ selectedScheduleInfo }}
                    </p>
                </div>

                <div class="mb-5">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        {{ t("booking_name") }} *
                    </label>
                    <input
                        v-model="form.name"
                        type="text"
                        :placeholder="t('booking_name_ph')"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-base focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50 focus:bg-white transition-all hover:border-blue-300"
                    />
                </div>

                <div class="mb-5">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        {{ t("booking_phone") }} *
                    </label>
                    <input
                        v-model="form.whatsapp"
                        type="tel"
                        :placeholder="t('booking_phone_ph')"
                        @input="validatePhone"
                        class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 bg-gray-50 focus:bg-white transition-all"
                        :class="
                            form.whatsapp && !phoneValid
                                ? 'border-red-400 focus:ring-red-300'
                                : 'border-gray-200 focus:ring-blue-400 hover:border-blue-300'
                        "
                    />
                    <p
                        v-if="form.whatsapp && !phoneValid"
                        class="text-red-500 text-xs mt-1.5"
                    >
                        ⚠️ رقم الواتساب غير صحيح — تأكد من الرقم
                    </p>
                    <p
                        v-if="form.whatsapp && phoneValid"
                        class="text-green-500 text-xs mt-1.5"
                    >
                        ✓ رقم صحيح
                    </p>
                </div>

                <!-- المستوى -->
                <div class="mb-5">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        {{ t("booking_level") }} *
                    </label>
                    <div
                        class="w-full border border-blue-200 rounded-xl px-4 py-3 text-base bg-blue-50 text-blue-800 font-semibold flex items-center justify-between"
                    >
                        <span>{{ form.level }}</span>
                        <span class="text-xs text-blue-500 font-normal"
                            >تم التحديد تلقائياً</span
                        >
                    </div>
                </div>

                <div class="mb-7">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        {{ t("booking_notes") }}
                    </label>
                    <textarea
                        v-model="form.notes"
                        rows="3"
                        :placeholder="t('booking_notes_ph')"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-base focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50 focus:bg-white transition-all resize-none hover:border-blue-300"
                    ></textarea>
                </div>

                <p
                    v-if="submitError"
                    class="text-red-500 text-sm text-center mb-4"
                >
                    {{ submitError }}
                </p>

                <button
                    @click="submit"
                    :disabled="
                        !form.name ||
                        !form.whatsapp ||
                        !form.level ||
                        !phoneValid ||
                        submitting
                    "
                    class="bg-blue-600 text-white px-6 py-4 rounded-xl font-bold w-full text-lg hover:bg-blue-700 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-blue-200"
                >
                    <span v-if="submitting">جاري الحجز...</span>
                    <span v-else>{{ t("booking_btn") }}</span>
                </button>

                <p class="text-xs text-gray-400 text-center mt-4">
                    {{ t("booking_note") }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useLang } from "@/i18n";
import axios from "axios";

const { t } = useLang();
const router = useRouter();
const route = useRoute();

const form = reactive({
    name: "",
    whatsapp: "",
    level: route.query.level || "",
    notes: "",
    schedule_id: route.query.schedule_id || null,
    course_id: route.query.course_id || null,
});

// هل المستوى اتحدد تلقائياً من صفحة الكورس
const isAutoLevel = computed(() => !!route.query.level);

// معلومات الموعد المختار
const selectedScheduleInfo = ref("");

const phoneValid = ref(false);
const submitting = ref(false);
const submitError = ref("");

// جلب تفاصيل الموعد لو اتحدد
async function fetchScheduleInfo() {
    if (!form.schedule_id) return;
    try {
        const response = await axios.get(
            `/api/v1/schedules/${form.schedule_id}`
        );
        const s = response.data.data;
        const days = {
            Saturday: "السبت",
            Sunday: "الأحد",
            Monday: "الاثنين",
            Tuesday: "الثلاثاء",
            Wednesday: "الأربعاء",
            Thursday: "الخميس",
            Friday: "الجمعة",
        };
        selectedScheduleInfo.value = `${s.title} — ${
            days[s.day_of_week]
        } ${s.start_time?.substring(0, 5)} إلى ${s.end_time?.substring(0, 5)}`;
    } catch {
        selectedScheduleInfo.value = "";
    }
}

function validatePhone(e) {
    const val = e.target.value.replace(/\s+/g, "");
    const egyptRegex = /^01[0125][0-9]{8}$/;
    const intlRegex = /^\+[1-9][0-9]{7,14}$/;
    phoneValid.value = egyptRegex.test(val) || intlRegex.test(val);
}

async function submit() {
    submitting.value = true;
    submitError.value = "";

    try {
        const response = await axios.post("/api/v1/bookings", {
            name: form.name,
            whatsapp: form.whatsapp,
            level: form.level,
            notes: form.notes,
            schedule_id: form.schedule_id || null,
        });

        const booking = {
            id: response.data.data.id,
            reference: response.data.data.reference,
            name: form.name,
            whatsapp: form.whatsapp,
            level: form.level,
            notes: form.notes,
            schedule_id: form.schedule_id,
        };

        sessionStorage.setItem("booking", JSON.stringify(booking));
        router.push("/payment");
    } catch (err) {
        console.error("خطأ في الحجز:", err);
        submitError.value =
            err?.response?.data?.message || "حصل خطأ في الاتصال، حاول تاني";
    } finally {
        submitting.value = false;
    }
}

onMounted(fetchScheduleInfo);
</script>
