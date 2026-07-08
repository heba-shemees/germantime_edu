<template>
    <div class="py-10 px-4 max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900">
                إدارة أسئلة اختبار تحديد المستوى
            </h1>
            <button
                @click="openCreateForm"
                class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-blue-700"
            >
                + سؤال جديد
            </button>
        </div>

        <!-- فورم الإضافة/التعديل -->
        <div
            v-if="showForm"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8"
        >
            <h2 class="font-bold text-lg mb-4">
                {{ editingId ? "تعديل السؤال" : "سؤال جديد" }}
            </h2>

            <div class="space-y-4">
                <div>
                    <label
                        class="block text-sm font-semibold text-gray-600 mb-1"
                    >
                        نص السؤال
                    </label>
                    <textarea
                        v-model="form.question_text"
                        rows="2"
                        class="w-full border border-gray-200 rounded-xl p-3"
                        placeholder="مثال: Wie heißt du? — ما معنى هذه الجملة؟"
                    ></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div v-for="opt in ['a', 'b', 'c', 'd']" :key="opt">
                        <label
                            class="block text-sm font-semibold text-gray-600 mb-1"
                        >
                            اختيار {{ opt.toUpperCase() }}
                        </label>
                        <input
                            v-model="form['option_' + opt]"
                            type="text"
                            class="w-full border border-gray-200 rounded-xl p-3"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-600 mb-1"
                        >
                            الإجابة الصحيحة
                        </label>
                        <select
                            v-model="form.correct_option"
                            class="w-full border border-gray-200 rounded-xl p-3"
                        >
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-600 mb-1"
                        >
                            مستوى السؤال
                        </label>
                        <select
                            v-model="form.level"
                            class="w-full border border-gray-200 rounded-xl p-3"
                        >
                            <option value="A1">A1</option>
                            <option value="A2">A2</option>
                            <option value="B1">B1</option>
                            <option value="B2">B2</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input
                        id="is_active"
                        v-model="form.is_active"
                        type="checkbox"
                        class="w-4 h-4"
                    />
                    <label for="is_active" class="text-sm text-gray-600">
                        مفعّل (يظهر في الاختبار)
                    </label>
                </div>

                <p v-if="formError" class="text-red-500 text-sm">
                    {{ formError }}
                </p>

                <div class="flex gap-3 justify-end">
                    <button
                        @click="closeForm"
                        class="px-5 py-2.5 rounded-xl font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50"
                    >
                        إلغاء
                    </button>
                    <button
                        @click="saveQuestion"
                        :disabled="saving"
                        class="px-5 py-2.5 rounded-xl font-semibold bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ saving ? "جاري الحفظ..." : "حفظ" }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="text-center text-gray-400 py-10">
            جاري تحميل الأسئلة...
        </div>

        <!-- قائمة الأسئلة -->
        <div v-else class="space-y-4">
            <div
                v-for="q in questions"
                :key="q.id"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span
                                class="px-2 py-0.5 rounded-full text-xs font-bold"
                                :class="levelBadge(q.level)"
                            >
                                {{ q.level }}
                            </span>
                            <span
                                v-if="!q.is_active"
                                class="px-2 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500"
                            >
                                غير مفعّل
                            </span>
                        </div>
                        <p class="font-semibold text-gray-900 mb-2">
                            {{ q.question_text }}
                        </p>
                        <div
                            class="grid grid-cols-2 gap-2 text-sm text-gray-500"
                        >
                            <span
                                v-for="opt in ['A', 'B', 'C', 'D']"
                                :key="opt"
                                :class="
                                    q.correct_option === opt
                                        ? 'text-green-600 font-bold'
                                        : ''
                                "
                            >
                                {{ opt }})
                                {{ q["option_" + opt.toLowerCase()] }}
                                <span v-if="q.correct_option === opt">✓</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <button
                            @click="editQuestion(q)"
                            class="text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg text-sm font-semibold"
                        >
                            تعديل
                        </button>
                        <button
                            @click="deleteQuestion(q.id)"
                            class="text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg text-sm font-semibold"
                        >
                            حذف
                        </button>
                    </div>
                </div>
            </div>

            <div
                v-if="!questions.length"
                class="text-center text-gray-400 py-10"
            >
                لا يوجد أسئلة حاليًا، ابدأ بإضافة سؤال جديد.
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const questions = ref([]);
const loading = ref(true);
const showForm = ref(false);
const editingId = ref(null);
const saving = ref(false);
const formError = ref("");

const emptyForm = () => ({
    question_text: "",
    option_a: "",
    option_b: "",
    option_c: "",
    option_d: "",
    correct_option: "A",
    level: "A1",
    is_active: true,
});

const form = ref(emptyForm());

async function fetchQuestions() {
    loading.value = true;
    try {
        const { data } = await axios.get("/api/v1/admin/test-questions");
        questions.value = data.data;
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
}

function openCreateForm() {
    editingId.value = null;
    form.value = emptyForm();
    formError.value = "";
    showForm.value = true;
}

function editQuestion(q) {
    editingId.value = q.id;
    form.value = {
        question_text: q.question_text,
        option_a: q.option_a,
        option_b: q.option_b,
        option_c: q.option_c,
        option_d: q.option_d,
        correct_option: q.correct_option,
        level: q.level,
        is_active: !!q.is_active,
    };
    formError.value = "";
    showForm.value = true;
}

function closeForm() {
    showForm.value = false;
}

async function saveQuestion() {
    saving.value = true;
    formError.value = "";
    try {
        if (editingId.value) {
            await axios.put(
                `/api/v1/admin/test-questions/${editingId.value}`,
                form.value
            );
        } else {
            await axios.post("/api/v1/admin/test-questions", form.value);
        }
        showForm.value = false;
        await fetchQuestions();
    } catch (e) {
        formError.value =
            e.response?.data?.message ||
            "حدث خطأ أثناء الحفظ، تأكد من البيانات";
    } finally {
        saving.value = false;
    }
}

async function deleteQuestion(id) {
    if (!confirm("متأكد إنك عايز تحذف السؤال ده؟")) return;
    try {
        await axios.delete(`/api/v1/admin/test-questions/${id}`);
        await fetchQuestions();
    } catch (e) {
        alert("حدث خطأ أثناء الحذف");
    }
}

function levelBadge(level) {
    return {
        A1: "bg-green-100 text-green-800",
        A2: "bg-blue-100 text-blue-800",
        B1: "bg-purple-100 text-purple-800",
        B2: "bg-orange-100 text-orange-800",
    }[level];
}

onMounted(fetchQuestions);
</script>
