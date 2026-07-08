<template>
    <div class="py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <div
                v-motion
                :initial="{ opacity: 0, y: 30 }"
                :enter="{ opacity: 1, y: 0, transition: { duration: 600 } }"
                class="text-center mb-12"
            >
                <h1 class="text-3xl font-bold text-gray-900 mb-3">
                    الكورسات المتاحة
                </h1>
                <p class="text-gray-500 text-lg">
                    اختار المستوى المناسب وابدأ رحلتك
                </p>
            </div>

            <div v-if="loading" class="text-center text-gray-400 py-10">
                جاري تحميل الكورسات...
            </div>

            <div v-else-if="error" class="text-center text-red-500 py-10">
                حدث خطأ أثناء تحميل الكورسات، حاول تاني.
            </div>

            <!-- 4 كروت رئيسية -->
            <div
                v-else
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
            >
                <div
                    v-for="(level, i) in mainLevels"
                    :key="level.code"
                    v-motion
                    :initial="{ opacity: 0, y: 40 }"
                    :visible="{
                        opacity: 1,
                        y: 0,
                        transition: { duration: 500, delay: i * 100 },
                    }"
                    @click="$router.push(`/courses/${level.code}`)"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 p-6 flex flex-col gap-4 cursor-pointer"
                >
                    <div class="flex items-center justify-between">
                        <span
                            class="px-3 py-1 rounded-full text-sm font-semibold"
                            :class="badgeClass(level.code)"
                        >
                            {{ level.code }}
                        </span>
                        <span class="text-xs text-gray-400">
                            {{ coursesCountFor(level.code) }} كورس متاح
                        </span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">
                            {{ level.name }}
                        </h3>
                        <p class="text-gray-500 text-sm mt-1">
                            {{ level.description }}
                        </p>
                    </div>
                    <div class="mt-auto">
                        <div class="text-2xl font-bold text-gray-900 mb-3">
                            {{ priceFromFor(level.code) }}
                            <span class="text-sm font-normal text-gray-400"
                                >جنيه</span
                            >
                        </div>
                        <button
                            class="bg-blue-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200 w-full text-sm"
                        >
                            تفاصيل الكورس ←
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const loading = ref(true);
const error = ref(false);
const courses = ref([]);

const mainLevels = [
    {
        code: "A1",
        name: "المبتدئ",
        description: "ابدأ من الصفر وتعلم أساسيات اللغة الألمانية",
    },
    {
        code: "A2",
        name: "الأساسي",
        description: "محادثة يومية وتوسيع المفردات",
    },
    {
        code: "B1",
        name: "المتوسط",
        description: "قواعد متقدمة والتعبير بطلاقة",
    },
    {
        code: "B2",
        name: "المتقدم",
        description: "طلاقة تامة في المحادثة والكتابة",
    },
];

function badgeClass(level) {
    return {
        A1: "bg-green-100 text-green-800",
        A2: "bg-blue-100 text-blue-800",
        B1: "bg-purple-100 text-purple-800",
        B2: "bg-orange-100 text-orange-800",
    }[level];
}

function coursesForLevel(level) {
    return courses.value.filter((c) => c.level === level);
}

function coursesCountFor(level) {
    return coursesForLevel(level).length;
}

function priceFromFor(level) {
    const list = coursesForLevel(level);
    if (!list.length) return "-";
    return Math.min(...list.map((c) => Number(c.price)));
}

async function fetchCourses() {
    loading.value = true;
    error.value = false;
    try {
        const { data } = await axios.get("/api/v1/courses");
        courses.value = data.data;
    } catch (e) {
        error.value = true;
    } finally {
        loading.value = false;
    }
}

onMounted(fetchCourses);
</script>
