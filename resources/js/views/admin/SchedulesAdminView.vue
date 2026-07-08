<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        📅 المواعيد
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">
                        إجمالي: {{ schedules.length }} موعد
                    </p>
                </div>
                <button
                    @click="openForm()"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition-all hover:scale-105 active:scale-95 shadow-md shadow-blue-200"
                >
                    + إضافة موعد
                </button>
            </div>

            <!-- فلتر المستوى -->
            <div class="flex gap-2 flex-wrap mb-6">
                <button
                    v-for="f in levelFilters"
                    :key="f"
                    @click="activeLevel = f"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-all"
                    :class="
                        activeLevel === f
                            ? 'bg-blue-600 text-white shadow-md'
                            : 'bg-white text-gray-600 border border-gray-200 hover:border-blue-300'
                    "
                >
                    {{ f === "all" ? "الكل" : f }}
                </button>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="space-y-4">
                <div
                    v-for="i in 3"
                    :key="i"
                    class="animate-pulse bg-white rounded-2xl h-28"
                ></div>
            </div>

            <!-- Empty -->
            <div
                v-else-if="filteredSchedules.length === 0"
                class="text-center py-20"
            >
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500">مفيش مواعيد</p>
            </div>

            <!-- قائمة المواعيد -->
            <div v-else class="space-y-4">
                <div
                    v-for="schedule in filteredSchedules"
                    :key="schedule.id"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition-all"
                >
                    <div
                        class="flex flex-wrap gap-4 items-start justify-between"
                    >
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3 flex-wrap">
                                <span class="font-bold text-gray-900 text-lg">{{
                                    schedule.title
                                }}</span>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-600"
                                >
                                    {{ schedule.course?.level }}
                                </span>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold"
                                    :class="
                                        schedule.is_full
                                            ? 'bg-red-100 text-red-600'
                                            : 'bg-green-100 text-green-600'
                                    "
                                >
                                    {{ schedule.is_full ? "مكتمل" : "متاح" }}
                                </span>
                                <span
                                    v-if="!schedule.is_active"
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500"
                                >
                                    غير نشط
                                </span>
                            </div>

                            <div
                                class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm text-gray-600"
                            >
                                <div
                                    class="bg-gray-50 rounded-xl p-3 text-center"
                                >
                                    <div class="text-xs text-gray-400 mb-1">
                                        اليوم
                                    </div>
                                    <div class="font-semibold">
                                        {{ dayName(schedule.day_of_week) }}
                                    </div>
                                </div>
                                <div
                                    class="bg-gray-50 rounded-xl p-3 text-center"
                                >
                                    <div class="text-xs text-gray-400 mb-1">
                                        الوقت
                                    </div>
                                    <div class="font-semibold">
                                        {{ formatTime(schedule.start_time) }} –
                                        {{ formatTime(schedule.end_time) }}
                                    </div>
                                </div>
                                <div
                                    class="bg-gray-50 rounded-xl p-3 text-center"
                                >
                                    <div class="text-xs text-gray-400 mb-1">
                                        الكراسي
                                    </div>
                                    <div class="font-semibold">
                                        <span
                                            :class="
                                                schedule.is_full
                                                    ? 'text-red-600'
                                                    : 'text-green-600'
                                            "
                                        >
                                            {{ schedule.available_seats }}
                                        </span>
                                        / {{ schedule.max_seats }}
                                    </div>
                                </div>
                                <div
                                    class="bg-gray-50 rounded-xl p-3 text-center"
                                >
                                    <div class="text-xs text-gray-400 mb-1">
                                        تاريخ البداية
                                    </div>
                                    <div class="font-semibold">
                                        {{ schedule.start_date }}
                                    </div>
                                </div>
                            </div>

                            <!-- Seats visual -->
                            <div class="flex gap-1 mt-3 flex-wrap">
                                <div
                                    v-for="i in schedule.max_seats"
                                    :key="i"
                                    class="w-4 h-4 rounded-sm"
                                    :class="
                                        i <=
                                        schedule.max_seats -
                                            schedule.available_seats
                                            ? 'bg-red-300'
                                            : 'bg-green-300'
                                    "
                                ></div>
                            </div>

                            <p
                                v-if="schedule.notes"
                                class="text-xs text-gray-400 mt-2 italic"
                            >
                                💬 {{ schedule.notes }}
                            </p>
                        </div>

                        <!-- أزرار -->
                        <div class="flex flex-col gap-2">
                            <button
                                @click="openForm(schedule)"
                                class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-100 transition-all"
                            >
                                ✏️ تعديل
                            </button>
                            <button
                                @click="toggleActive(schedule)"
                                class="px-4 py-2 rounded-xl text-sm font-bold transition-all"
                                :class="
                                    schedule.is_active
                                        ? 'bg-orange-50 text-orange-600 hover:bg-orange-100'
                                        : 'bg-green-50 text-green-600 hover:bg-green-100'
                                "
                            >
                                {{ schedule.is_active ? "⏸ إيقاف" : "▶ تفعيل" }}
                            </button>
                            <button
                                @click="deleteSchedule(schedule)"
                                class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-100 transition-all"
                            >
                                🗑 حذف
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal إضافة/تعديل -->
        <div
            v-if="showForm"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
            @click.self="showForm = false"
        >
            <div
                class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg max-h-[90vh] overflow-y-auto"
            >
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    {{
                        editingSchedule ? "✏️ تعديل موعد" : "+ إضافة موعد جديد"
                    }}
                </h2>

                <div class="space-y-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >المستوى *</label
                        >
                        <select
                            v-model="formData.course_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                        >
                            <option value="" disabled>اختار المستوى</option>
                            <option
                                v-for="course in courses"
                                :key="course.id"
                                :value="course.id"
                            >
                                {{ course.level }} — {{ course.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >عنوان الموعد *</label
                        >
                        <input
                            v-model="formData.title"
                            type="text"
                            placeholder="مثال: كورس A1 - مجموعة صباحية"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >اليوم *</label
                            >
                            <select
                                v-model="formData.day_of_week"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                            >
                                <option value="" disabled>اختار</option>
                                <option
                                    v-for="d in days"
                                    :key="d.value"
                                    :value="d.value"
                                >
                                    {{ d.label }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >عدد الكراسي *</label
                            >
                            <input
                                v-model.number="formData.max_seats"
                                type="number"
                                min="1"
                                max="50"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >وقت البداية *</label
                            >
                            <input
                                v-model="formData.start_time"
                                type="time"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >وقت النهاية *</label
                            >
                            <input
                                v-model="formData.end_time"
                                type="time"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >تاريخ البداية *</label
                            >
                            <input
                                v-model="formData.start_date"
                                type="date"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >تاريخ النهاية *</label
                            >
                            <input
                                v-model="formData.end_date"
                                type="date"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >ملاحظات</label
                        >
                        <textarea
                            v-model="formData.notes"
                            rows="2"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50 resize-none"
                        ></textarea>
                    </div>
                </div>

                <p
                    v-if="formError"
                    class="text-red-500 text-sm mt-4 text-center"
                >
                    {{ formError }}
                </p>

                <div class="flex gap-3 mt-6">
                    <button
                        @click="showForm = false"
                        class="flex-1 border-2 border-gray-200 text-gray-600 py-3 rounded-xl font-bold hover:border-gray-300 transition-all"
                    >
                        إلغاء
                    </button>
                    <button
                        @click="saveSchedule"
                        :disabled="saving"
                        class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all disabled:opacity-40"
                    >
                        {{
                            saving
                                ? "جاري الحفظ..."
                                : editingSchedule
                                ? "حفظ التعديل"
                                : "إضافة"
                        }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

const schedules = ref([]);
const courses = ref([]);
const loading = ref(true);
const showForm = ref(false);
const saving = ref(false);
const formError = ref("");
const editingSchedule = ref(null);
const activeLevel = ref("all");

const levelFilters = ["all", "A1", "A2", "B1", "B2"];

const days = [
    { value: "Saturday", label: "السبت" },
    { value: "Sunday", label: "الأحد" },
    { value: "Monday", label: "الاثنين" },
    { value: "Tuesday", label: "الثلاثاء" },
    { value: "Wednesday", label: "الأربعاء" },
    { value: "Thursday", label: "الخميس" },
    { value: "Friday", label: "الجمعة" },
];

const defaultForm = {
    course_id: "",
    title: "",
    day_of_week: "",
    start_time: "",
    end_time: "",
    start_date: "",
    end_date: "",
    max_seats: 10,
    notes: "",
};

const formData = ref({ ...defaultForm });

const filteredSchedules = computed(() =>
    activeLevel.value === "all"
        ? schedules.value
        : schedules.value.filter((s) => s.course?.level === activeLevel.value)
);

async function fetchSchedules() {
    loading.value = true;
    try {
        const response = await axios.get("/api/v1/admin/schedules");
        const list = Array.isArray(response.data)
            ? response.data
            : response.data.data || [];
        schedules.value = list;
    } catch (err) {
        console.error("خطأ في جلب المواعيد", err);
    } finally {
        loading.value = false;
    }
}

async function fetchCourses() {
    try {
        const response = await axios.get("/api/v1/courses");
        const list = Array.isArray(response.data)
            ? response.data
            : response.data.data || [];
        courses.value = list;
    } catch (err) {
        console.error("خطأ في جلب الكورسات", err);
    }
}

function openForm(schedule = null) {
    editingSchedule.value = schedule;
    formError.value = "";
    if (schedule) {
        formData.value = {
            course_id: schedule.course?.id || "",
            title: schedule.title,
            day_of_week: schedule.day_of_week,
            start_time: schedule.start_time?.substring(0, 5),
            end_time: schedule.end_time?.substring(0, 5),
            start_date: schedule.start_date,
            end_date: schedule.end_date,
            max_seats: schedule.max_seats,
            notes: schedule.notes || "",
        };
    } else {
        formData.value = { ...defaultForm };
    }
    showForm.value = true;
}

async function saveSchedule() {
    if (
        !formData.value.course_id ||
        !formData.value.title ||
        !formData.value.day_of_week ||
        !formData.value.start_time ||
        !formData.value.end_time ||
        !formData.value.start_date ||
        !formData.value.end_date
    ) {
        formError.value = "برجاء ملء جميع الحقول المطلوبة";
        return;
    }

    saving.value = true;
    formError.value = "";

    try {
        if (editingSchedule.value) {
            const { data } = await axios.put(
                `/api/v1/admin/schedules/${editingSchedule.value.id}`,
                formData.value
            );
            const idx = schedules.value.findIndex(
                (s) => s.id === editingSchedule.value.id
            );
            if (idx !== -1)
                schedules.value[idx] = {
                    ...schedules.value[idx],
                    ...data.data,
                };
        } else {
            const { data } = await axios.post(
                "/api/v1/admin/schedules",
                formData.value
            );
            await fetchSchedules();
        }
        showForm.value = false;
    } catch (err) {
        formError.value = err?.response?.data?.message || "حصل خطأ، حاول تاني";
    } finally {
        saving.value = false;
    }
}

async function toggleActive(schedule) {
    try {
        await axios.put(`/api/v1/admin/schedules/${schedule.id}`, {
            is_active: !schedule.is_active,
        });
        schedule.is_active = !schedule.is_active;
    } catch (err) {
        alert("حصل خطأ");
    }
}

async function deleteSchedule(schedule) {
    if (!confirm(`حذف موعد "${schedule.title}"؟`)) return;
    try {
        await axios.delete(`/api/v1/admin/schedules/${schedule.id}`);
        schedules.value = schedules.value.filter((s) => s.id !== schedule.id);
    } catch (err) {
        alert(err?.response?.data?.message || "حصل خطأ في الحذف");
    }
}

function dayName(day) {
    return days.find((d) => d.value === day)?.label || day;
}

function formatTime(time) {
    return time?.substring(0, 5);
}

onMounted(() => {
    fetchSchedules();
    fetchCourses();
});
</script>
