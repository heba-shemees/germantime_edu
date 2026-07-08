<template>
    <div class="py-16 px-4 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-900 mb-3">
                    📅 المواعيد المتاحة
                </h1>
                <p class="text-gray-500">اختار المستوى وشوف المواعيد</p>
            </div>

            <!-- فلتر المستوى -->
            <div class="flex gap-3 justify-center flex-wrap mb-8">
                <button
                    v-for="f in levelFilters"
                    :key="f"
                    @click="activeLevel = f"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all"
                    :class="
                        activeLevel === f
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
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
                    class="animate-pulse bg-white rounded-2xl h-32"
                ></div>
            </div>

            <!-- Empty -->
            <div
                v-else-if="filteredSchedules.length === 0"
                class="text-center py-20"
            >
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500">مفيش مواعيد متاحة دلوقتي</p>
                <p class="text-xs text-gray-400 mt-2">
                    تواصل معنا على واتساب لمعرفة المواعيد القادمة
                </p>
            </div>

            <!-- قائمة المواعيد -->
            <div v-else class="space-y-4">
                <div
                    v-for="schedule in filteredSchedules"
                    :key="schedule.id"
                    class="bg-white rounded-2xl border shadow-sm p-6 transition-all hover:shadow-md"
                    :class="
                        schedule.is_full
                            ? 'border-gray-100 opacity-75'
                            : 'border-gray-100'
                    "
                >
                    <div
                        class="flex flex-wrap gap-4 items-center justify-between"
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
                                    {{
                                        schedule.is_full
                                            ? "🔴 مكتمل"
                                            : "🟢 متاح"
                                    }}
                                </span>
                            </div>

                            <div
                                class="flex flex-wrap gap-4 text-sm text-gray-600"
                            >
                                <span
                                    >📅
                                    {{ dayName(schedule.day_of_week) }}</span
                                >
                                <span
                                    >🕐 {{ formatTime(schedule.start_time) }} –
                                    {{ formatTime(schedule.end_time) }}</span
                                >
                                <span>📆 يبدأ {{ schedule.start_date }}</span>
                            </div>

                            <p
                                v-if="schedule.notes"
                                class="text-xs text-gray-400 mt-2 italic"
                            >
                                💬 {{ schedule.notes }}
                            </p>
                        </div>

                        <!-- زر الحجز -->
                        <button
                            v-if="!schedule.is_full"
                            @click="bookSchedule(schedule)"
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all shadow-md shadow-blue-200"
                        >
                            احجز الآن ←
                        </button>
                        <div
                            v-else
                            class="bg-gray-100 text-gray-400 px-6 py-3 rounded-xl font-bold cursor-not-allowed"
                        >
                            مكتمل
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();
const schedules = ref([]);
const loading = ref(true);
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

const filteredSchedules = computed(() =>
    activeLevel.value === "all"
        ? schedules.value
        : schedules.value.filter((s) => s.course?.level === activeLevel.value)
);

async function fetchSchedules() {
    loading.value = true;
    try {
        const response = await axios.get("/api/v1/schedules");
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

function bookSchedule(schedule) {
    router.push({
        path: "/booking",
        query: {
            level: schedule.course?.level,
            schedule_id: schedule.id,
        },
    });
}

function dayName(day) {
    return days.find((d) => d.value === day)?.label || day;
}

function formatTime(time) {
    return time?.substring(0, 5);
}

onMounted(fetchSchedules);
</script>
