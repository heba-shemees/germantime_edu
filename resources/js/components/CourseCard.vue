# CourseCard.vue @'
<template>
    <div
        class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all p-6 flex flex-col gap-4"
    >
        <div class="flex items-center justify-between">
            <span
                class="px-3 py-1 rounded-full text-sm font-semibold"
                :class="badgeClass"
                >{{ course.level }}</span
            >
            <span class="text-xs text-gray-400">{{ course.duration }}</span>
        </div>
        <div>
            <h3 class="font-bold text-lg text-gray-900">{{ course.name }}</h3>
            <p class="text-gray-500 text-sm mt-1">{{ course.description }}</p>
        </div>
        <div class="mt-auto">
            <div class="text-2xl font-bold text-gray-900 mb-3">
                {{ course.price }}
                <span class="text-sm font-normal text-gray-400">جنيه</span>
            </div>
            <RouterLink
                :to="`/courses/${course.id}`"
                class="bg-blue-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-blue-700 transition-all block text-center text-sm"
                >تفاصيل الكورس</RouterLink
            >
        </div>
    </div>
</template>
<script setup>
import { computed } from "vue";
const props = defineProps({ course: { type: Object, required: true } });
const badgeClass = computed(
    () =>
        ({
            A1: "bg-green-100 text-green-800",
            A2: "bg-blue-100 text-blue-800",
            B1: "bg-purple-100 text-purple-800",
            B2: "bg-orange-100 text-orange-800",
        }[props.course.level])
);
</script>
'@ | Set-Content -Encoding UTF8 "resources/js/components/CourseCard.vue"
