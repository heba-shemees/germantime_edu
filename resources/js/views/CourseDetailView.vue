<template>
    <div class="py-16 px-4 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto">
            <!-- Back -->
            <button
                @click="$router.push('/courses')"
                class="flex items-center gap-2 text-gray-500 hover:text-blue-600 mb-8 transition-colors text-sm font-medium"
            >
                ← رجوع للكورسات
            </button>

            <!-- Header -->
            <div class="text-center mb-12">
                <span
                    class="px-4 py-2 rounded-full text-lg font-bold inline-block mb-4"
                    :class="badgeClass(levelCode)"
                >
                    {{ levelCode }}
                </span>
                <h1 class="text-3xl font-bold text-gray-900 mb-3">
                    كورسات مستوى {{ levelCode }}
                </h1>
                <p class="text-gray-500">اختار الكورس والمواعيد المناسبة لك</p>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="space-y-6">
                <div
                    v-for="i in 3"
                    :key="i"
                    class="animate-pulse bg-white rounded-2xl h-48"
                ></div>
            </div>

            <!-- Empty -->
            <div
                v-else-if="groupedCourses.length === 0"
                class="text-center py-20"
            >
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500">
                    مفيش مواعيد متاحة دلوقتي لهذا المستوى
                </p>
            </div>

            <!-- الكورسات مقسمة -->
            <div v-else class="space-y-8">
                <div
                    v-for="course in groupedCourses"
                    :key="course.id"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                >
                    <!-- Header الكورس -->
                    <div
                        class="bg-gradient-to-l p-6 border-b border-gray-100"
                        :class="headerGradient(levelCode)"
                    >
                        <div
                            class="flex items-center justify-between flex-wrap gap-3"
                        >
                            <div>
                                <div
                                    class="flex items-center gap-3 mb-1 flex-wrap"
                                >
                                    <h3 class="font-bold text-xl text-gray-900">
                                        {{ course.name }}
                                    </h3>
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-white/80"
                                        :class="textBadgeClass(levelCode)"
                                    >
                                        {{ course.schedules.length }} موعد
                                    </span>
                                </div>
                                <p
                                    v-if="course.description"
                                    class="text-gray-600 text-sm"
                                >
                                    {{ course.description }}
                                </p>
                            </div>
                            <div v-if="course.price" class="text-left">
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ course.price }}
                                </div>
                                <div class="text-xs text-gray-500">جنيه</div>
                            </div>
                        </div>
                    </div>

                    <!-- المواعيد -->
                    <div class="p-4 space-y-3">
                        <div
                            v-if="course.schedules.length === 0"
                            class="text-center py-6 text-gray-400 text-sm"
                        >
                            مفيش مواعيد متاحة دلوقتي
                        </div>

                        <div
                            v-for="sch in course.schedules"
                            :key="sch.id"
                            class="bg-gray-50 rounded-xl p-4 transition-all"
                            :class="
                                sch.is_full ? 'opacity-75' : 'hover:bg-gray-100'
                            "
                        >
                            <div
                                class="flex items-center justify-between flex-wrap gap-3"
                            >
                                <div
                                    class="flex items-center gap-4 flex-wrap text-sm"
                                >
                                    <span class="font-bold text-gray-900">{{
                                        sch.title
                                    }}</span>
                                    <span class="text-gray-600"
                                        >📅 {{ dayName(sch.day_of_week) }}</span
                                    >
                                    <span class="text-gray-600"
                                        >🕐 {{ formatTime(sch.start_time) }} –
                                        {{ formatTime(sch.end_time) }}</span
                                    >
                                </div>

                                <div class="flex items-center gap-3">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold"
                                        :class="
                                            sch.is_full
                                                ? 'bg-red-100 text-red-600'
                                                : 'bg-green-100 text-green-600'
                                        "
                                    >
                                        {{
                                            sch.is_full ? "🔴 مكتمل" : "🟢 متاح"
                                        }}
                                    </span>
                                    <button
                                        v-if="!sch.is_full"
                                        @click="bookSchedule(course, sch)"
                                        class="bg-blue-600 text-white px-5 py-2 rounded-xl font-bold text-sm hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all"
                                    >
                                        احجز الآن ←
                                    </button>
                                </div>
                            </div>

                            <!-- تفاصيل المدة -->
                            <div
                                class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-200 flex-wrap text-xs text-gray-500"
                            >
                                <span>📆 يبدأ {{ sch.start_date }}</span>
                                <span>🏁 ينتهي {{ sch.end_date }}</span>
                                <span class="font-semibold text-gray-700"
                                    >⏱
                                    {{
                                        calcWeeks(sch.start_date, sch.end_date)
                                    }}
                                    أسبوع</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const loading = ref(true);
const courses = ref([]);

// ✅ بقت computed عشان تتحدث تلقائيًا لو المستوى اتغير من غير ما الصفحة تعمل reload كامل
const levelCode = computed(() => route.params.level);

const days = [
    { value: "Saturday", label: "السبت" },
    { value: "Sunday", label: "الأحد" },
    { value: "Monday", label: "الاثنين" },
    { value: "Tuesday", label: "الثلاثاء" },
    { value: "Wednesday", label: "الأربعاء" },
    { value: "Thursday", label: "الخميس" },
    { value: "Friday", label: "الجمعة" },
];

const groupedCourses = computed(() => {
    const list = courses.value.filter(
        (c) => c.schedules && c.schedules.length > 0
    );
    return list.sort((a, b) => {
        if (a.name === levelCode.value) return -1;
        if (b.name === levelCode.value) return 1;
        return a.name.localeCompare(b.name);
    });
});

async function fetchCourses() {
    loading.value = true;
    try {
        const response = await axios.get("/api/v1/courses", {
            params: { level: levelCode.value },
        });
        const list = Array.isArray(response.data)
            ? response.data
            : response.data.data || [];

        const withSchedules = await Promise.all(
            list.map(async (course) => {
                try {
                    const sRes = await axios.get("/api/v1/schedules", {
                        params: { course_id: course.id },
                    });
                    const schedules = Array.isArray(sRes.data)
                        ? sRes.data
                        : sRes.data.data || [];

                    return { ...course, schedules };
                } catch {
                    return { ...course, schedules: [] };
                }
            })
        );

        courses.value = withSchedules;
    } catch (err) {
        console.error("خطأ في جلب الكورسات", err);
    } finally {
        loading.value = false;
    }
}

function bookSchedule(course, sch) {
    router.push({
        path: "/booking",
        query: {
            level: levelCode.value,
            course_id: course.id,
            schedule_id: sch.id,
        },
    });
}

function calcWeeks(startDate, endDate) {
    if (!startDate || !endDate) return "—";
    const start = new Date(startDate);
    const end = new Date(endDate);
    const diffDays = Math.round((end - start) / (1000 * 60 * 60 * 24));
    const weeks = Math.round(diffDays / 7);
    return weeks > 0 ? weeks : 1;
}

function dayName(day) {
    return days.find((d) => d.value === day)?.label || day;
}

function formatTime(time) {
    return time?.substring(0, 5);
}

function badgeClass(level) {
    const base = level?.split(".")[0];
    return (
        {
            A1: "bg-green-100 text-green-800",
            A2: "bg-blue-100 text-blue-800",
            B1: "bg-purple-100 text-purple-800",
            B2: "bg-orange-100 text-orange-800",
        }[base] || "bg-gray-100 text-gray-800"
    );
}

function textBadgeClass(level) {
    const base = level?.split(".")[0];
    return (
        {
            A1: "text-green-700",
            A2: "text-blue-700",
            B1: "text-purple-700",
            B2: "text-orange-700",
        }[base] || "text-gray-700"
    );
}

function headerGradient(level) {
    const base = level?.split(".")[0];
    return (
        {
            A1: "from-green-50 to-white",
            A2: "from-blue-50 to-white",
            B1: "from-purple-50 to-white",
            B2: "from-orange-50 to-white",
        }[base] || "from-gray-50 to-white"
    );
}

// ✅ إعادة جلب البيانات لو المستوى اتغير (بدون reload كامل للصفحة)
watch(levelCode, fetchCourses);

onMounted(fetchCourses);
</script>
