<template>
    <div
        class="min-h-screen bg-gradient-to-b from-blue-50 to-white py-16 px-4 overflow-hidden"
    >
        <div class="max-w-xl mx-auto text-center">
            <div v-if="result">
                <div
                    v-motion
                    :initial="{ opacity: 0, scale: 0 }"
                    :enter="{
                        opacity: 1,
                        scale: 1,
                        transition: {
                            duration: 600,
                            type: 'spring',
                            stiffness: 150,
                        },
                    }"
                    class="text-6xl mb-6"
                >
                    🎉
                </div>
                <div
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :enter="{
                        opacity: 1,
                        y: 0,
                        transition: { duration: 500, delay: 200 },
                    }"
                >
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">
                        {{ t("result_title") }}
                    </h1>
                    <p class="text-gray-500 mb-8">
                        {{ result.score }} {{ t("result_score") }}
                    </p>
                </div>
                <div
                    v-motion
                    :initial="{ opacity: 0, scale: 0.8 }"
                    :enter="{
                        opacity: 1,
                        scale: 1,
                        transition: { duration: 600, delay: 300 },
                    }"
                    class="bg-white rounded-2xl border border-gray-100 shadow-xl p-10 mb-8"
                >
                    <div
                        class="text-7xl font-extrabold mb-3"
                        :class="levelColor"
                    >
                        {{ result.level }}
                    </div>
                    <div class="text-xl font-semibold text-gray-800 mb-2">
                        {{ levelName }}
                    </div>
                    <p class="text-gray-500 text-sm mb-6">{{ levelDesc }}</p>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div
                            class="h-3 rounded-full transition-all duration-1000"
                            :class="levelBar"
                            :style="{
                                width: animated ? result.score + '%' : '0%',
                            }"
                        ></div>
                    </div>
                </div>
                <div
                    v-motion
                    :initial="{ opacity: 0, y: 20 }"
                    :enter="{
                        opacity: 1,
                        y: 0,
                        transition: { duration: 500, delay: 500 },
                    }"
                    class="space-y-3"
                >
                    <RouterLink
                        :to="`/courses/${result.course_id}`"
                        class="bg-blue-600 text-white px-10 py-4 rounded-xl font-bold text-lg hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200 inline-block shadow-lg shadow-blue-200 w-full"
                    >
                        {{ t("result_cta") }} {{ result.level }} ←
                    </RouterLink>
                    <div>
                        <RouterLink
                            to="/test"
                            class="text-blue-600 text-sm hover:underline"
                            >{{ t("result_retry") }}</RouterLink
                        >
                    </div>
                </div>
            </div>
            <div v-else>
                <p class="text-gray-500 mb-4">{{ t("result_no") }}</p>
                <RouterLink
                    to="/test"
                    class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-all inline-block"
                    >{{ t("result_start") }}</RouterLink
                >
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, computed, onMounted } from "vue";
import { useLang } from "@/i18n";
const { t } = useLang();
const saved = sessionStorage.getItem("testResult");
const result = computed(() => (saved ? JSON.parse(saved) : null));
const animated = ref(false);
onMounted(() => setTimeout(() => (animated.value = true), 800));
const levelNames = {
    A1: "level_a1",
    A2: "level_a2",
    B1: "level_b1",
    B2: "level_b2",
};
const levelDescs = {
    A1: "desc_a1",
    A2: "desc_a2",
    B1: "desc_b1",
    B2: "desc_b2",
};
const levelName = computed(() => t(levelNames[result.value?.level]));
const levelDesc = computed(() => t(levelDescs[result.value?.level]));
const levelColor = computed(
    () =>
        ({
            A1: "text-green-600",
            A2: "text-blue-600",
            B1: "text-purple-600",
            B2: "text-orange-600",
        }[result.value?.level])
);
const levelBar = computed(
    () =>
        ({
            A1: "bg-green-400",
            A2: "bg-blue-400",
            B1: "bg-purple-400",
            B2: "bg-orange-400",
        }[result.value?.level])
);
</script>
