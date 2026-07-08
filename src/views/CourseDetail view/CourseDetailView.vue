<template>
    <div class="py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Loading -->
            <div v-if="coursesStore.loading" class="animate-pulse space-y-6">
                <div class="h-8 bg-gray-200 rounded w-1/3"></div>
                <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                <div class="h-64 bg-gray-200 rounded-2xl"></div>
            </div>

            <!-- Error -->
            <div v-else-if="coursesStore.error" class="text-center py-20">
                <div class="text-5xl mb-4">😞</div>
                <p class="text-gray-500 mb-4">{{ coursesStore.error }}</p>
                <RouterLink to="/courses" class="btn-primary"
                    >ارجع للكورسات</RouterLink
                >
            </div>

            <!-- Course content -->
            <div v-else-if="course">
                <!-- Back -->
                <RouterLink
                    to="/courses"
                    class="inline-flex items-center gap-2 text-brand-600 text-sm font-medium mb-8 hover:underline"
                >
                    ← كل الكورسات
                </RouterLink>

                <!-- Hero -->
                <div class="card p-8 mb-8" :class="heroBg">
                    <div
                        class="flex items-start justify-between gap-4 flex-wrap"
                    >
                        <div>
                            <span
                                class="level-badge text-lg mb-3 inline-block"
                                :class="badgeClass"
                                >{{ course.level }}</span
                            >
                            <h1 class="text-3xl font-bold text-gray-900 mb-3">
                                {{ course.name }}
                            </h1>
                            <p class="text-gray-600 leading-relaxed max-w-xl">
                                {{ course.description }}
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-extrabold text-brand-700">
                                {{ course.price }}
                            </div>
                            <div class="text-gray-500 text-sm">جنيه</div>
                        </div>
                    </div>
                </div>

                <!-- Info grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="card p-5 text-center">
                        <div class="text-2xl mb-1">📅</div>
                        <div class="font-bold text-gray-900">
                            {{ course.duration_weeks }}
                        </div>
                        <div class="text-xs text-gray-500">أسبوع</div>
                    </div>
                    <div class="card p-5 text-center">
                        <div class="text-2xl mb-1">🔄</div>
                        <div class="font-bold text-gray-900">
                            {{ course.sessions_per_week }}
                        </div>
                        <div class="text-xs text-gray-500">حصص / أسبوع</div>
                    </div>
                    <div class="card p-5 text-center">
                        <div class="text-2xl mb-1">⏱️</div>
                        <div class="font-bold text-gray-900">
                            {{ course.session_duration_minutes }}
                        </div>
                        <div class="text-xs text-gray-500">دقيقة / حصة</div>
                    </div>
                    <div class="card p-5 text-center">
                        <div class="text-2xl mb-1">💻</div>
                        <div class="font-bold text-gray-900">أونلاين</div>
                        <div class="text-xs text-gray-500">Zoom / Meet</div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <!-- Learning outcomes -->
                    <div class="card p-6">
                        <h2 class="font-bold text-gray-900 text-lg mb-4">
                            🎯 هتتعلم إيه؟
                        </h2>
                        <ul class="space-y-3">
                            <li
                                v-for="(outcome, i) in course.learning_outcomes"
                                :key="i"
                                class="flex items-start gap-3 text-gray-600 text-sm"
                            >
                                <span
                                    class="text-accent-500 mt-0.5 flex-shrink-0"
                                    >✓</span
                                >
                                {{ outcome }}
                            </li>
                        </ul>
                    </div>

                    <!-- Schedule -->
                    <div class="card p-6">
                        <h2 class="font-bold text-gray-900 text-lg mb-4">
                            🗓️ المواعيد
                        </h2>
                        <div class="space-y-3">
                            <div
                                v-for="(s, i) in course.schedule"
                                :key="i"
                                class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3"
                            >
                                <span class="font-medium text-gray-800">{{
                                    s.day
                                }}</span>
                                <span class="text-brand-600 font-semibold">{{
                                    s.time
                                }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-4">
                            * المواعيد قابلة للتعديل حسب الاتفاق
                        </p>
                    </div>
                </div>

                <!-- Testimonials -->
                <div v-if="course.testimonials?.length" class="mb-8">
                    <h2 class="font-bold text-gray-900 text-xl mb-5">
                        💬 ماذا قالت الطلبة؟
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div
                            v-for="t in course.testimonials"
                            :key="t.id"
                            class="card p-5"
                        >
                            <div class="flex gap-1 text-yellow-400 mb-2">
                                <span v-for="i in t.rating" :key="i">⭐</span>
                            </div>
                            <p
                                class="text-gray-600 text-sm leading-relaxed mb-3"
                            >
                                "{{ t.body }}"
                            </p>
                            <div class="font-semibold text-sm text-gray-800">
                                {{ t.student_name }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="card p-8 text-center bg-brand-50 border-brand-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">
                        جاهز للبدأ؟
                    </h2>
                    <p class="text-gray-500 mb-6">
                        احجز مكانك دلوقتي قبل ما الأماكن تخلص
                    </p>
                    <RouterLink
                        :to="`/booking?level=${course.level}&course_id=${course.id}`"
                        class="btn-primary text-lg px-10 py-4"
                    >
                        احجز الآن ←
                    </RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import { useCoursesStore } from "@/stores/courses";

const route = useRoute();
const coursesStore = useCoursesStore();
const course = computed(() => coursesStore.current);

const badgeClass = computed(
    () =>
        ({
            A1: "bg-green-100 text-green-800",
            A2: "bg-blue-100 text-blue-800",
            B1: "bg-purple-100 text-purple-800",
            B2: "bg-orange-100 text-orange-800",
        }[course.value?.level] || "bg-gray-100 text-gray-700")
);

const heroBg = computed(
    () =>
        ({
            A1: "bg-green-50 border-green-100",
            A2: "bg-blue-50 border-blue-100",
            B1: "bg-purple-50 border-purple-100",
            B2: "bg-orange-50 border-orange-100",
        }[course.value?.level] || "")
);

function load() {
    coursesStore.fetchOne(route.params.id);
}

onMounted(load);
watch(() => route.params.id, load);
</script>
