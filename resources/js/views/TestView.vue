<template>
    <div class="py-16 px-4 min-h-screen bg-gray-50">
        <div class="max-w-2xl mx-auto">
            <!-- Loading -->
            <div v-if="loading" class="text-center py-24">
                <div class="text-4xl mb-4 animate-pulse">📝</div>
                <p class="text-gray-500">جاري تجهيز الاختبار...</p>
            </div>

            <!-- Error -->
            <div v-else-if="error" class="text-center py-24">
                <p class="text-red-500 mb-4">حدث خطأ أثناء تحميل الاختبار</p>
                <button
                    @click="fetchQuestions"
                    class="bg-blue-600 text-white px-5 py-2 rounded-xl font-semibold hover:bg-blue-700"
                >
                    حاول تاني
                </button>
            </div>

            <!-- Submitting -->
            <div v-else-if="submitting" class="text-center py-24">
                <div class="text-4xl mb-4 animate-pulse">📊</div>
                <p class="text-gray-500">جاري حساب نتيجتك...</p>
            </div>

            <!-- Test -->
            <div v-else-if="questions.length">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        اختبار تحديد المستوى
                    </h1>
                    <p class="text-gray-500">
                        سؤال {{ currentIndex + 1 }} من {{ questions.length }}
                    </p>
                </div>

                <!-- Progress bar -->
                <div
                    class="w-full h-2 bg-gray-200 rounded-full mb-10 overflow-hidden"
                >
                    <div
                        class="h-full bg-blue-600 transition-all duration-300"
                        :style="{ width: progressPercent + '%' }"
                    ></div>
                </div>

                <!-- السؤال -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8"
                >
                    <h2
                        class="text-xl font-bold text-gray-900 mb-6 text-center"
                    >
                        {{ currentQuestion.question_text }}
                    </h2>

                    <div class="space-y-3">
                        <button
                            v-for="optionKey in ['A', 'B', 'C', 'D']"
                            :key="optionKey"
                            @click="selectOption(optionKey)"
                            class="w-full flex items-center justify-between border rounded-xl px-5 py-4 text-right transition-all"
                            :class="
                                answers[currentQuestion.id] === optionKey
                                    ? 'border-blue-600 bg-blue-50 text-blue-700 font-semibold'
                                    : 'border-gray-200 hover:border-blue-300 hover:bg-gray-50 text-gray-700'
                            "
                        >
                            <span>{{
                                currentQuestion.options[optionKey]
                            }}</span>
                            <span
                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border"
                                :class="
                                    answers[currentQuestion.id] === optionKey
                                        ? 'bg-blue-600 text-white border-blue-600'
                                        : 'border-gray-300 text-gray-400'
                                "
                            >
                                {{ optionKey }}
                            </span>
                        </button>
                    </div>
                </div>

                <!-- التنقل -->
                <div class="flex items-center justify-between mt-8">
                    <button
                        @click="goBack"
                        :disabled="currentIndex === 0"
                        class="px-6 py-3 rounded-xl font-semibold border border-gray-200 text-gray-600 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-100 transition-all"
                    >
                        ← السابق
                    </button>

                    <span class="text-sm text-gray-400">
                        {{ answeredCount }} / {{ questions.length }} تم الإجابة
                    </span>

                    <button
                        v-if="currentIndex < questions.length - 1"
                        @click="goNext"
                        :disabled="!isCurrentAnswered"
                        class="px-6 py-3 rounded-xl font-semibold bg-blue-600 text-white disabled:opacity-40 disabled:cursor-not-allowed hover:bg-blue-700 transition-all"
                    >
                        التالي →
                    </button>
                    <button
                        v-else
                        @click="submitTest"
                        :disabled="!isCurrentAnswered"
                        class="px-6 py-3 rounded-xl font-semibold bg-green-600 text-white disabled:opacity-40 disabled:cursor-not-allowed hover:bg-green-700 transition-all"
                    >
                        إنهاء الاختبار ✓
                    </button>
                </div>
            </div>

            <!-- مفيش أسئلة -->
            <div v-else class="text-center py-24">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500">لا يوجد اختبار متاح حاليًا</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();

const loading = ref(true);
const submitting = ref(false);
const error = ref(false);
const questions = ref([]);
const currentIndex = ref(0);

// answers = { [question_id]: 'A' | 'B' | 'C' | 'D' }
const answers = ref({});

const currentQuestion = computed(() => questions.value[currentIndex.value]);

const isCurrentAnswered = computed(
    () => !!answers.value[currentQuestion.value?.id]
);

const answeredCount = computed(() => Object.keys(answers.value).length);

const progressPercent = computed(() => {
    if (!questions.value.length) return 0;
    return Math.round(
        ((currentIndex.value + 1) / questions.value.length) * 100
    );
});

function selectOption(optionKey) {
    answers.value[currentQuestion.value.id] = optionKey;
}

function goNext() {
    if (currentIndex.value < questions.value.length - 1) {
        currentIndex.value++;
    }
}

function goBack() {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
}

async function fetchQuestions() {
    loading.value = true;
    error.value = false;
    try {
        const { data } = await axios.get("/api/v1/test/questions");
        questions.value = data.data;
        currentIndex.value = 0;
        answers.value = {};
    } catch (e) {
        error.value = true;
    } finally {
        loading.value = false;
    }
}

async function submitTest() {
    submitting.value = true;
    try {
        const payload = {
            answers: Object.entries(answers.value).map(
                ([question_id, selected_option]) => ({
                    question_id: Number(question_id),
                    selected_option,
                })
            ),
        };

        const { data } = await axios.post("/api/v1/test/submit", payload);

        sessionStorage.setItem(
            "testResult",
            JSON.stringify({
                score: data.percentage,
                level: data.level,
                course_id: data.level, // صفحة الكورسات شغالة بالمستوى (/courses/:level)
            })
        );

        router.push({ name: "test-result" });
    } catch (e) {
        submitting.value = false;
        error.value = true;
    }
}

onMounted(fetchQuestions);
</script>
