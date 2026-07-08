<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Admin Navigation Bar -->
        <div
            class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-40"
        >
            <div
                class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between"
            >
                <h1 class="text-lg font-bold text-gray-900">🎓 لوحة التحكم</h1>
                <div class="flex gap-2">
                    <button
                        @click="
                            activeTab = 'bookings';
                            fetchBookings();
                        "
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                        :class="
                            activeTab === 'bookings'
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                        "
                    >
                        📋 الحجوزات
                        <span
                            class="bg-white/30 text-xs px-2 py-0.5 rounded-full"
                            v-if="activeTab === 'bookings'"
                        >
                            {{ bookings.length }}
                        </span>
                    </button>
                    <button
                        @click="
                            activeTab = 'schedules';
                            fetchSchedules();
                        "
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                        :class="
                            activeTab === 'schedules'
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                        "
                    >
                        📅 المواعيد
                        <span
                            class="bg-white/30 text-xs px-2 py-0.5 rounded-full"
                            v-if="activeTab === 'schedules'"
                        >
                            {{ schedules.length }}
                        </span>
                    </button>
                    <button
                        @click="
                            activeTab = 'questions';
                            fetchQuestions();
                        "
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                        :class="
                            activeTab === 'questions'
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                        "
                    >
                        📝 أسئلة الاختبار
                        <span
                            class="bg-white/30 text-xs px-2 py-0.5 rounded-full"
                            v-if="activeTab === 'questions'"
                        >
                            {{ questions.length }}
                        </span>
                    </button>
                    <button
                        @click="
                            activeTab = 'users';
                            fetchDashboardUsers();
                        "
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all"
                        :class="
                            activeTab === 'users'
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                        "
                    >
                        👥 المستخدمين
                        <span
                            class="bg-white/30 text-xs px-2 py-0.5 rounded-full"
                            v-if="activeTab === 'users'"
                        >
                            {{ dashboardUsers.length }}
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto p-6">
            <!-- TAB: الحجوزات -->
            <div v-if="activeTab === 'bookings'">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-gray-500 text-sm">
                        إجمالي: {{ bookings.length }} حجز
                    </p>
                    <div class="flex gap-2 flex-wrap">
                        <button
                            v-for="f in filters"
                            :key="f.value"
                            @click="activeFilter = f.value"
                            class="px-4 py-2 rounded-xl text-sm font-semibold transition-all"
                            :class="
                                activeFilter === f.value
                                    ? 'bg-blue-600 text-white shadow-md'
                                    : 'bg-white text-gray-600 border border-gray-200 hover:border-blue-300'
                            "
                        >
                            {{ f.label }}
                            <span class="ml-1 opacity-70"
                                >({{ countByStatus(f.value) }})</span
                            >
                        </button>
                    </div>
                </div>

                <div v-if="loading" class="space-y-4">
                    <div
                        v-for="i in 4"
                        :key="i"
                        class="animate-pulse bg-white rounded-2xl h-32"
                    ></div>
                </div>

                <div
                    v-else-if="filteredBookings.length === 0"
                    class="text-center py-20"
                >
                    <div class="text-5xl mb-4">📭</div>
                    <p class="text-gray-500">مفيش حجوزات في هذه الحالة</p>
                </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="booking in filteredBookings"
                        :key="booking.id"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 transition-all hover:shadow-md"
                    >
                        <div
                            class="flex flex-wrap gap-4 items-start justify-between"
                        >
                            <div class="flex-1 min-w-0">
                                <div
                                    class="flex items-center gap-3 mb-3 flex-wrap"
                                >
                                    <span
                                        class="font-bold text-gray-900 text-lg"
                                        >{{ booking.name }}</span
                                    >
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold"
                                        :class="statusClass(booking.status)"
                                    >
                                        {{ statusLabel(booking.status) }}
                                    </span>
                                    <span
                                        class="text-xs text-gray-400 font-mono"
                                        >{{ booking.reference }}</span
                                    >
                                </div>
                                <div
                                    class="grid grid-cols-2 gap-x-6 gap-y-1 text-sm text-gray-600"
                                >
                                    <div>📱 {{ booking.whatsapp }}</div>
                                    <div>
                                        📚 المستوى:
                                        <span class="font-semibold">{{
                                            booking.level
                                        }}</span>
                                    </div>
                                    <div v-if="booking.schedule">
                                        📅 {{ booking.schedule.title }}
                                    </div>
                                    <div>
                                        🕐 {{ timeAgo(booking.created_at) }}
                                    </div>
                                    <div
                                        v-if="booking.notes"
                                        class="col-span-2 text-gray-500 italic"
                                    >
                                        💬 {{ booking.notes }}
                                    </div>
                                </div>
                                <div
                                    v-if="booking.status === 'pending'"
                                    class="mt-3"
                                >
                                    <div
                                        class="flex items-center gap-2 text-orange-600 text-xs font-semibold"
                                    >
                                        <span>⏳ ينتهي:</span>
                                        <span>{{
                                            formatExpiry(booking.expires_at)
                                        }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-center gap-3">
                                <div
                                    v-if="booking.receipt_path"
                                    class="cursor-pointer"
                                    @click="openReceipt(booking.receipt_path)"
                                >
                                    <img
                                        :src="`/storage/${booking.receipt_path}`"
                                        alt="إيصال"
                                        class="w-24 h-24 object-cover rounded-xl border-2 border-gray-200 hover:border-blue-400 transition-all hover:scale-105"
                                    />
                                    <p
                                        class="text-xs text-blue-600 text-center mt-1"
                                    >
                                        اضغط للتكبير
                                    </p>
                                </div>
                                <div
                                    v-else
                                    class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-400 text-xs text-center"
                                >
                                    لم يرفع إيصال
                                </div>

                                <div
                                    v-if="booking.status === 'uploaded'"
                                    class="flex gap-2"
                                >
                                    <button
                                        @click="confirmBooking(booking)"
                                        :disabled="booking.loading"
                                        class="bg-green-500 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-green-600 transition-all disabled:opacity-50"
                                    >
                                        ✓ موافقة
                                    </button>
                                    <button
                                        @click="cancelBooking(booking)"
                                        :disabled="booking.loading"
                                        class="bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-600 transition-all disabled:opacity-50"
                                    >
                                        ✕ رفض
                                    </button>
                                </div>

                                <button
                                    v-if="booking.status === 'cancelled'"
                                    @click="restoreBooking(booking)"
                                    :disabled="booking.loading"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-600 transition-all disabled:opacity-50"
                                >
                                    🔄 استعادة
                                </button>

                                <a
                                    v-if="booking.status === 'confirmed'"
                                    :href="waLink(booking)"
                                    target="_blank"
                                    class="bg-green-500 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-green-600 transition-all flex items-center gap-1"
                                >
                                    💬 واتساب
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB: المواعيد -->
            <div v-if="activeTab === 'schedules'">
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <button
                            @click="activeLevel = 'all'"
                            class="px-4 py-2 rounded-xl text-sm font-bold transition-all border-2"
                            :class="
                                activeLevel === 'all'
                                    ? 'bg-gray-800 text-white border-gray-800'
                                    : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400'
                            "
                        >
                            الكل
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <div
                            v-for="group in levelGroups"
                            :key="group.main"
                            class="flex items-center gap-1 bg-white rounded-xl border border-gray-100 shadow-sm p-1"
                        >
                            <button
                                @click="activeLevel = group.main"
                                class="px-3 py-1.5 rounded-lg text-sm font-bold transition-all"
                                :class="
                                    activeLevel === group.main
                                        ? groupActiveClass(group.main)
                                        : 'text-gray-600 hover:bg-gray-100'
                                "
                            >
                                {{ group.main }}
                            </button>
                            <div class="w-px h-5 bg-gray-200"></div>
                            <button
                                v-for="sub in group.subs"
                                :key="sub"
                                @click="activeLevel = sub"
                                class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all"
                                :class="
                                    activeLevel === sub
                                        ? groupActiveClass(group.main)
                                        : 'text-gray-500 hover:bg-gray-100'
                                "
                            >
                                {{ sub }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-500">
                        عرض:
                        <span class="font-semibold text-gray-900">{{
                            activeLevel === "all" ? "الكل" : activeLevel
                        }}</span>
                        — {{ filteredSchedules.length }} موعد
                    </p>
                    <button
                        @click="openScheduleForm()"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md shadow-blue-200"
                    >
                        + إضافة موعد
                    </button>
                </div>

                <div v-if="schedulesLoading" class="space-y-4">
                    <div
                        v-for="i in 3"
                        :key="i"
                        class="animate-pulse bg-white rounded-2xl h-28"
                    ></div>
                </div>

                <div
                    v-else-if="filteredSchedules.length === 0"
                    class="text-center py-20"
                >
                    <div class="text-5xl mb-4">📭</div>
                    <p class="text-gray-500">مفيش مواعيد لهذا المستوى</p>
                </div>

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
                                <div
                                    class="flex items-center gap-3 mb-3 flex-wrap"
                                >
                                    <span
                                        class="font-bold text-gray-900 text-lg"
                                        >{{ schedule.title }}</span
                                    >
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold"
                                        :class="
                                            groupBadgeClass(
                                                schedule.course?.level
                                            )
                                        "
                                    >
                                        {{
                                            schedule.course?.name ||
                                            schedule.course?.level
                                        }}
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
                                            schedule.is_full ? "مكتمل" : "متاح"
                                        }}
                                    </span>
                                    <span
                                        v-if="!schedule.is_active"
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500"
                                    >
                                        غير نشط
                                    </span>
                                </div>

                                <div
                                    class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm"
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
                                            {{
                                                formatTime(schedule.start_time)
                                            }}
                                            –
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
                                                >{{
                                                    schedule.available_seats
                                                }}</span
                                            >
                                            / {{ schedule.max_seats }}
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-50 rounded-xl p-3 text-center"
                                    >
                                        <div class="text-xs text-gray-400 mb-1">
                                            البداية
                                        </div>
                                        <div class="font-semibold">
                                            {{ schedule.start_date }}
                                        </div>
                                    </div>
                                </div>

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
                            </div>

                            <div class="flex flex-col gap-2">
                                <button
                                    @click="openScheduleForm(schedule)"
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
                                    {{
                                        schedule.is_active
                                            ? "⏸ إيقاف"
                                            : "▶ تفعيل"
                                    }}
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

            <!-- TAB: أسئلة الاختبار -->
            <div v-if="activeTab === 'questions'">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-gray-500 text-sm">
                        إجمالي: {{ questions.length }} سؤال
                    </p>
                    <button
                        @click="openCreateQuestionForm"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md shadow-blue-200"
                    >
                        + سؤال جديد
                    </button>
                </div>

                <!-- فورم الإضافة/التعديل -->
                <div
                    v-if="showQuestionForm"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8"
                >
                    <h2 class="font-bold text-lg mb-4">
                        {{ editingQuestionId ? "تعديل السؤال" : "سؤال جديد" }}
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-600 mb-1"
                            >
                                نص السؤال
                            </label>
                            <textarea
                                v-model="questionForm.question_text"
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
                                    v-model="questionForm['option_' + opt]"
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
                                    v-model="questionForm.correct_option"
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
                                    v-model="questionForm.level"
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
                                id="q_is_active"
                                v-model="questionForm.is_active"
                                type="checkbox"
                                class="w-4 h-4"
                            />
                            <label
                                for="q_is_active"
                                class="text-sm text-gray-600"
                            >
                                مفعّل (يظهر في الاختبار)
                            </label>
                        </div>

                        <p
                            v-if="questionFormError"
                            class="text-red-500 text-sm"
                        >
                            {{ questionFormError }}
                        </p>

                        <div class="flex gap-3 justify-end">
                            <button
                                @click="showQuestionForm = false"
                                class="px-5 py-2.5 rounded-xl font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50"
                            >
                                إلغاء
                            </button>
                            <button
                                @click="saveQuestion"
                                :disabled="questionSaving"
                                class="px-5 py-2.5 rounded-xl font-semibold bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50"
                            >
                                {{ questionSaving ? "جاري الحفظ..." : "حفظ" }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Loading -->
                <div v-if="questionsLoading" class="space-y-4">
                    <div
                        v-for="i in 3"
                        :key="i"
                        class="animate-pulse bg-white rounded-2xl h-28"
                    ></div>
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
                                        :class="groupBadgeClass(q.level)"
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
                                        <span v-if="q.correct_option === opt"
                                            >✓</span
                                        >
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-2 shrink-0">
                                <button
                                    @click="editQuestion(q)"
                                    class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-100 transition-all"
                                >
                                    ✏️ تعديل
                                </button>
                                <button
                                    @click="deleteQuestion(q.id)"
                                    class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-100 transition-all"
                                >
                                    🗑 حذف
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="!questions.length" class="text-center py-20">
                        <div class="text-5xl mb-4">📭</div>
                        <p class="text-gray-500">
                            لا يوجد أسئلة حاليًا، ابدأ بإضافة سؤال جديد
                        </p>
                    </div>
                </div>
            </div>

            <!-- TAB: المستخدمين -->
            <div v-if="activeTab === 'users'">
                <div
                    class="flex items-center justify-between mb-6 gap-4 flex-wrap"
                >
                    <p class="text-gray-500 text-sm">
                        إجمالي: {{ dashboardUsers.length }} مستخدم
                    </p>
                    <div class="relative w-full sm:w-72">
                        <input
                            v-model="userSearch"
                            @input="debouncedSearchUsers"
                            type="text"
                            placeholder="🔍 ابحث بالاسم أو الإيميل أو الهاتف..."
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        />
                    </div>
                </div>

                <div v-if="usersLoading" class="space-y-4">
                    <div
                        v-for="i in 3"
                        :key="i"
                        class="animate-pulse bg-white rounded-2xl h-40"
                    ></div>
                </div>

                <div
                    v-else-if="!dashboardUsers.length"
                    class="text-center py-20"
                >
                    <div class="text-5xl mb-4">📭</div>
                    <p class="text-gray-500">
                        {{
                            userSearch
                                ? "لا يوجد نتائج مطابقة للبحث"
                                : "لا يوجد مستخدمين مسجلين حاليًا"
                        }}
                    </p>
                </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="u in dashboardUsers"
                        :key="u.id"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                    >
                        <!-- بيانات أساسية -->
                        <div
                            class="flex flex-wrap items-start justify-between gap-4 mb-4"
                        >
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">
                                    {{ u.name }}
                                </h3>
                                <div
                                    class="flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-600 mt-1"
                                >
                                    <span>✉️ {{ u.email }}</span>
                                    <span v-if="u.phone">📱 {{ u.phone }}</span>
                                    <span v-else class="text-gray-400"
                                        >📱 لا يوجد رقم بعد</span
                                    >
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <!-- نتيجة اختبار تحديد المستوى -->
                                <div v-if="u.test_result" class="text-center">
                                    <div
                                        class="px-4 py-2 rounded-xl text-sm font-bold"
                                        :class="
                                            levelBadgeClass(u.test_result.level)
                                        "
                                    >
                                        نتيجة الاختبار:
                                        {{ u.test_result.level }} ({{
                                            u.test_result.percentage
                                        }}%)
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ formatDate(u.test_result.date) }}
                                    </p>
                                </div>
                                <div v-else class="text-xs text-gray-400">
                                    لسه ماعملش اختبار تحديد مستوى
                                </div>

                                <button
                                    @click="openEditUserForm(u)"
                                    class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-100 transition-all whitespace-nowrap"
                                >
                                    ✏️ تعديل
                                </button>
                                <button
                                    @click="deleteUser(u)"
                                    :disabled="u.deleting"
                                    class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-100 transition-all whitespace-nowrap disabled:opacity-50"
                                >
                                    {{ u.deleting ? "جاري الحذف..." : "🗑 حذف" }}
                                </button>
                            </div>
                        </div>

                        <!-- الكورسات المسجل فيها -->
                        <div v-if="u.courses.length" class="mb-4">
                            <p class="text-xs font-semibold text-gray-400 mb-2">
                                الكورسات المسجل فيها
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="c in u.courses"
                                    :key="c.id"
                                    class="px-3 py-1 rounded-full text-xs font-bold"
                                    :class="levelBadgeClass(c.level)"
                                >
                                    {{ c.name }}
                                </span>
                            </div>
                        </div>

                        <!-- الحجوزات وحالة الدفع -->
                        <div v-if="u.bookings.length">
                            <p class="text-xs font-semibold text-gray-400 mb-2">
                                الحجوزات وحالة الدفع
                            </p>
                            <div class="space-y-2">
                                <div
                                    v-for="b in u.bookings"
                                    :key="b.id"
                                    class="bg-gray-50 rounded-xl p-3 flex flex-wrap items-center justify-between gap-2 text-sm"
                                >
                                    <div
                                        class="flex items-center gap-3 flex-wrap"
                                    >
                                        <span
                                            class="font-mono text-xs text-gray-400"
                                        >
                                            {{ b.reference }}
                                        </span>
                                        <span v-if="b.schedule">
                                            📅 {{ b.schedule.title }}
                                        </span>
                                        <span class="text-gray-500">
                                            📚 {{ b.level }}
                                        </span>
                                    </div>
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold"
                                        :class="statusClass(b.status)"
                                    >
                                        {{ statusLabel(b.status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-xs text-gray-400">
                            لسه معملش أي حجز
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal إيصال -->
        <div
            v-if="receiptModal"
            class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4"
            @click="receiptModal = null"
        >
            <div class="relative max-w-2xl w-full" @click.stop>
                <img
                    :src="`/storage/${receiptModal}`"
                    class="w-full rounded-2xl shadow-2xl"
                    alt="إيصال"
                />
                <button
                    @click="receiptModal = null"
                    class="absolute -top-4 -right-4 bg-white text-gray-800 w-9 h-9 rounded-full shadow-lg font-bold hover:bg-gray-100 transition-all"
                >
                    ✕
                </button>
            </div>
        </div>

        <!-- Modal تعديل بيانات طالب -->
        <div
            v-if="showUserForm"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
            @click.self="showUserForm = false"
        >
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    ✏️ تعديل بيانات الطالب
                </h2>

                <div class="space-y-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >الاسم *</label
                        >
                        <input
                            v-model="userForm.name"
                            type="text"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >الإيميل *</label
                        >
                        <input
                            v-model="userForm.email"
                            type="email"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2"
                            >رقم الهاتف</label
                        >
                        <input
                            v-model="userForm.phone"
                            type="text"
                            placeholder="01xxxxxxxxx"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                        />
                    </div>
                </div>

                <p
                    v-if="userFormError"
                    class="text-red-500 text-sm mt-4 text-center"
                >
                    {{ userFormError }}
                </p>

                <div class="flex gap-3 mt-6">
                    <button
                        @click="showUserForm = false"
                        class="flex-1 border-2 border-gray-200 text-gray-600 py-3 rounded-xl font-bold hover:border-gray-300 transition-all"
                    >
                        إلغاء
                    </button>
                    <button
                        @click="saveUser"
                        :disabled="userSaving"
                        class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all disabled:opacity-40"
                    >
                        {{ userSaving ? "جاري الحفظ..." : "حفظ التعديل" }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal إضافة/تعديل موعد -->
        <div
            v-if="showScheduleForm"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
            @click.self="showScheduleForm = false"
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
                            v-model="scheduleForm.course_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                        >
                            <option value="" disabled>اختار المستوى</option>
                            <optgroup
                                v-for="group in levelGroups"
                                :key="group.main"
                                :label="'مستوى ' + group.main"
                            >
                                <option
                                    v-for="course in getCoursesByGroup(
                                        group.main
                                    )"
                                    :key="course.id"
                                    :value="course.id"
                                >
                                    {{ course.name }}
                                </option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >عدد الكراسي *</label
                            >
                            <input
                                v-model.number="scheduleForm.max_seats"
                                type="number"
                                min="1"
                                max="50"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >اليوم</label
                            >
                            <div
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-100 text-gray-500 text-sm"
                            >
                                {{
                                    scheduleForm.day_of_week
                                        ? dayName(scheduleForm.day_of_week)
                                        : "يتحدد تلقائياً"
                                }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >وقت البداية *</label
                            >
                            <input
                                v-model="scheduleForm.start_time"
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
                                v-model="scheduleForm.end_time"
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
                                v-model="scheduleForm.start_date"
                                type="date"
                                @change="updateDayFromDate"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-2"
                                >تاريخ النهاية *</label
                            >
                            <input
                                v-model="scheduleForm.end_date"
                                type="date"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-50"
                            />
                        </div>
                    </div>
                </div>

                <p
                    v-if="scheduleFormError"
                    class="text-red-500 text-sm mt-4 text-center"
                >
                    {{ scheduleFormError }}
                </p>

                <div class="flex gap-3 mt-6">
                    <button
                        @click="showScheduleForm = false"
                        class="flex-1 border-2 border-gray-200 text-gray-600 py-3 rounded-xl font-bold hover:border-gray-300 transition-all"
                    >
                        إلغاء
                    </button>
                    <button
                        @click="saveSchedule"
                        :disabled="scheduleSaving"
                        class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all disabled:opacity-40"
                    >
                        {{
                            scheduleSaving
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

const activeTab = ref("bookings");

// ══════════════════════════════════════════════
// LEVELS CONFIG
// ══════════════════════════════════════════════
const levelGroups = [
    { main: "A1", subs: ["A1.1", "A1.2"] },
    { main: "A2", subs: ["A2.1", "A2.2"] },
    { main: "B1", subs: ["B1.1", "B1.2"] },
    { main: "B2", subs: ["B2.1", "B2.2"] },
];

function groupActiveClass(main) {
    return (
        {
            A1: "bg-green-600 text-white",
            A2: "bg-blue-600 text-white",
            B1: "bg-purple-600 text-white",
            B2: "bg-orange-600 text-white",
        }[main] || "bg-gray-600 text-white"
    );
}

function groupBadgeClass(levelName) {
    if (!levelName) return "bg-gray-100 text-gray-600";
    const main = levelName.split(".")[0];
    return (
        {
            A1: "bg-green-100 text-green-700",
            A2: "bg-blue-100 text-blue-700",
            B1: "bg-purple-100 text-purple-700",
            B2: "bg-orange-100 text-orange-700",
        }[main] || "bg-gray-100 text-gray-600"
    );
}

function getCoursesByGroup(mainLevel) {
    return courses.value.filter((c) => c.level === mainLevel);
}

// ══════════════════════════════════════════════
// BOOKINGS
// ══════════════════════════════════════════════
const bookings = ref([]);
const loading = ref(true);
const activeFilter = ref("all");
const receiptModal = ref(null);

const filters = [
    { label: "الكل", value: "all" },
    { label: "⏳ معلق", value: "pending" },
    { label: "📎 رفع إيصال", value: "uploaded" },
    { label: "✅ مؤكد", value: "confirmed" },
    { label: "❌ ملغي", value: "cancelled" },
];

async function fetchBookings() {
    loading.value = true;
    try {
        const response = await axios.get("/api/v1/admin/bookings");
        const list = Array.isArray(response.data)
            ? response.data
            : response.data.data || [];
        bookings.value = list.map((b) => ({ ...b, loading: false }));
    } catch (err) {
        console.error("خطأ في جلب الحجوزات", err);
    } finally {
        loading.value = false;
    }
}

const filteredBookings = computed(() =>
    activeFilter.value === "all"
        ? bookings.value
        : bookings.value.filter((b) => b.status === activeFilter.value)
);

function countByStatus(status) {
    if (status === "all") return bookings.value.length;
    return bookings.value.filter((b) => b.status === status).length;
}

async function confirmBooking(booking) {
    if (!confirm(`تأكيد موافقة حجز ${booking.name}؟`)) return;
    booking.loading = true;
    try {
        await axios.patch(`/api/v1/admin/bookings/${booking.id}/confirm`);
        booking.status = "confirmed";
    } catch {
        alert("حصل خطأ");
    } finally {
        booking.loading = false;
    }
}

async function cancelBooking(booking) {
    if (!confirm(`رفض وإلغاء حجز ${booking.name}؟`)) return;
    booking.loading = true;
    try {
        await axios.patch(`/api/v1/admin/bookings/${booking.id}/cancel`);
        booking.status = "cancelled";
    } catch {
        alert("حصل خطأ");
    } finally {
        booking.loading = false;
    }
}

async function restoreBooking(booking) {
    if (!confirm(`استعادة حجز ${booking.name}؟`)) return;
    booking.loading = true;
    try {
        await axios.patch(`/api/v1/admin/bookings/${booking.id}/restore`);
        booking.status = booking.receipt_path ? "uploaded" : "pending";
    } catch {
        alert("حصل خطأ");
    } finally {
        booking.loading = false;
    }
}

function waLink(booking) {
    const msg = `✅ مرحباً ${booking.name}!\n\nتم تأكيد حجزك في كورس الألماني 🎉\n\n📚 المستوى: ${booking.level}\n🔖 رقم الحجز: ${booking.reference}\n\nسيتم إرسال لينك الكلاس قبل الموعد بـ 24 ساعة إن شاء الله 🙏`;
    return `https://wa.me/2${booking.whatsapp}?text=${encodeURIComponent(msg)}`;
}

function openReceipt(path) {
    receiptModal.value = path;
}

function statusLabel(status) {
    return (
        {
            pending: "معلق",
            uploaded: "رفع إيصال",
            confirmed: "مؤكد",
            cancelled: "ملغي",
        }[status] || status
    );
}

function statusClass(status) {
    return (
        {
            pending: "bg-orange-100 text-orange-600",
            uploaded: "bg-blue-100 text-blue-600",
            confirmed: "bg-green-100 text-green-600",
            cancelled: "bg-red-100 text-red-600",
        }[status] || "bg-gray-100 text-gray-600"
    );
}

function formatExpiry(expires_at) {
    if (!expires_at) return "";
    const diff = new Date(expires_at) - Date.now();
    if (diff <= 0) return "انتهى الوقت";
    const h = Math.floor(diff / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    return `بعد ${h} ساعة ${m} دقيقة`;
}

function timeAgo(date) {
    if (!date) return "";
    const diff = Date.now() - new Date(date);
    const m = Math.floor(diff / 60000);
    const h = Math.floor(diff / 3600000);
    const d = Math.floor(diff / 86400000);
    if (m < 60) return `منذ ${m} دقيقة`;
    if (h < 24) return `منذ ${h} ساعة`;
    return `منذ ${d} يوم`;
}

// ══════════════════════════════════════════════
// SCHEDULES
// ══════════════════════════════════════════════
const schedules = ref([]);
const courses = ref([]);
const schedulesLoading = ref(false);
const showScheduleForm = ref(false);
const scheduleSaving = ref(false);
const scheduleFormError = ref("");
const editingSchedule = ref(null);
const activeLevel = ref("all");

const days = [
    { value: "Saturday", label: "السبت" },
    { value: "Sunday", label: "الأحد" },
    { value: "Monday", label: "الاثنين" },
    { value: "Tuesday", label: "الثلاثاء" },
    { value: "Wednesday", label: "الأربعاء" },
    { value: "Thursday", label: "الخميس" },
    { value: "Friday", label: "الجمعة" },
];

const defaultScheduleForm = {
    course_id: "",
    day_of_week: "",
    start_time: "",
    end_time: "",
    start_date: "",
    end_date: "",
    max_seats: 10,
};

const scheduleForm = ref({ ...defaultScheduleForm });

// تحديد اليوم تلقائياً من تاريخ البداية
function updateDayFromDate() {
    if (!scheduleForm.value.start_date) return;
    const date = new Date(scheduleForm.value.start_date);
    const dayIndex = date.getUTCDay();
    const dayMap = {
        0: "Sunday",
        1: "Monday",
        2: "Tuesday",
        3: "Wednesday",
        4: "Thursday",
        5: "Friday",
        6: "Saturday",
    };
    scheduleForm.value.day_of_week = dayMap[dayIndex];
}

// الفلترة — لو اختار A1 يعرض A1 + A1.1 + A1.2
const filteredSchedules = computed(() => {
    if (activeLevel.value === "all") return schedules.value;

    const selected = activeLevel.value;
    const isMain = !selected.includes(".");

    return schedules.value.filter((s) => {
        const courseName = s.course?.name || "";
        const courseLevel = s.course?.level || "";

        if (isMain) {
            return courseLevel === selected || courseName.startsWith(selected);
        } else {
            return courseName === selected;
        }
    });
});

async function fetchSchedules() {
    schedulesLoading.value = true;
    try {
        const response = await axios.get("/api/v1/admin/schedules");
        const list = Array.isArray(response.data)
            ? response.data
            : response.data.data || [];
        schedules.value = list;
    } catch (err) {
        console.error("خطأ في جلب المواعيد", err);
    } finally {
        schedulesLoading.value = false;
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

function openScheduleForm(schedule = null) {
    editingSchedule.value = schedule;
    scheduleFormError.value = "";
    if (schedule) {
        scheduleForm.value = {
            course_id: schedule.course?.id || "",
            day_of_week: schedule.day_of_week,
            start_time: schedule.start_time?.substring(0, 5),
            end_time: schedule.end_time?.substring(0, 5),
            start_date: schedule.start_date,
            end_date: schedule.end_date,
            max_seats: schedule.max_seats,
        };
    } else {
        scheduleForm.value = { ...defaultScheduleForm };
    }
    showScheduleForm.value = true;
}

async function saveSchedule() {
    const f = scheduleForm.value;
    if (
        !f.course_id ||
        !f.day_of_week ||
        !f.start_time ||
        !f.end_time ||
        !f.start_date ||
        !f.end_date
    ) {
        scheduleFormError.value = "برجاء ملء جميع الحقول المطلوبة";
        return;
    }
    scheduleSaving.value = true;
    scheduleFormError.value = "";
    try {
        const selectedCourse = courses.value.find((c) => c.id === f.course_id);
        const payload = {
            ...f,
            title: selectedCourse ? `كورس ${selectedCourse.name}` : "كورس",
        };

        if (editingSchedule.value) {
            await axios.put(
                `/api/v1/admin/schedules/${editingSchedule.value.id}`,
                payload
            );
        } else {
            await axios.post("/api/v1/admin/schedules", payload);
        }
        await fetchSchedules();

        const savedCourse = courses.value.find((c) => c.id === f.course_id);
        if (savedCourse) activeLevel.value = savedCourse.name;

        showScheduleForm.value = false;
    } catch (err) {
        scheduleFormError.value =
            err?.response?.data?.message || "حصل خطأ، حاول تاني";
    } finally {
        scheduleSaving.value = false;
    }
}

async function toggleActive(schedule) {
    try {
        await axios.put(`/api/v1/admin/schedules/${schedule.id}`, {
            is_active: !schedule.is_active,
        });
        schedule.is_active = !schedule.is_active;
    } catch {
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

// ══════════════════════════════════════════════
// TEST QUESTIONS
// ══════════════════════════════════════════════
const questions = ref([]);
const questionsLoading = ref(false);
const showQuestionForm = ref(false);
const editingQuestionId = ref(null);
const questionSaving = ref(false);
const questionFormError = ref("");

const emptyQuestionForm = () => ({
    question_text: "",
    option_a: "",
    option_b: "",
    option_c: "",
    option_d: "",
    correct_option: "A",
    level: "A1",
    is_active: true,
});

const questionForm = ref(emptyQuestionForm());

async function fetchQuestions() {
    questionsLoading.value = true;
    try {
        const { data } = await axios.get("/api/v1/admin/test-questions");
        questions.value = data.data;
    } catch (err) {
        console.error("خطأ في جلب الأسئلة", err);
    } finally {
        questionsLoading.value = false;
    }
}

function openCreateQuestionForm() {
    editingQuestionId.value = null;
    questionForm.value = emptyQuestionForm();
    questionFormError.value = "";
    showQuestionForm.value = true;
}

function editQuestion(q) {
    editingQuestionId.value = q.id;
    questionForm.value = {
        question_text: q.question_text,
        option_a: q.option_a,
        option_b: q.option_b,
        option_c: q.option_c,
        option_d: q.option_d,
        correct_option: q.correct_option,
        level: q.level,
        is_active: !!q.is_active,
    };
    questionFormError.value = "";
    showQuestionForm.value = true;
}

async function saveQuestion() {
    questionSaving.value = true;
    questionFormError.value = "";
    try {
        if (editingQuestionId.value) {
            await axios.put(
                `/api/v1/admin/test-questions/${editingQuestionId.value}`,
                questionForm.value
            );
        } else {
            await axios.post(
                "/api/v1/admin/test-questions",
                questionForm.value
            );
        }
        showQuestionForm.value = false;
        await fetchQuestions();
    } catch (err) {
        questionFormError.value =
            err.response?.data?.message ||
            "حدث خطأ أثناء الحفظ، تأكد من البيانات";
    } finally {
        questionSaving.value = false;
    }
}

async function deleteQuestion(id) {
    if (!confirm("متأكد إنك عايز تحذف السؤال ده؟")) return;
    try {
        await axios.delete(`/api/v1/admin/test-questions/${id}`);
        questions.value = questions.value.filter((q) => q.id !== id);
    } catch {
        alert("حدث خطأ أثناء الحذف");
    }
}

// ══════════════════════════════════════════════
// DASHBOARD USERS
// ══════════════════════════════════════════════
const dashboardUsers = ref([]);
const usersLoading = ref(false);
const userSearch = ref("");
let searchTimeout = null;

async function fetchDashboardUsers() {
    usersLoading.value = true;
    try {
        const { data } = await axios.get("/api/v1/admin/dashboard/users", {
            params: userSearch.value ? { search: userSearch.value } : {},
        });
        dashboardUsers.value = data.data.map((u) => ({
            ...u,
            deleting: false,
        }));
    } catch (err) {
        console.error("خطأ في جلب بيانات المستخدمين", err);
    } finally {
        usersLoading.value = false;
    }
}

function debouncedSearchUsers() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchDashboardUsers();
    }, 400);
}

// ── تعديل بيانات طالب ──
const showUserForm = ref(false);
const userSaving = ref(false);
const userFormError = ref("");
const editingUserId = ref(null);
const userForm = ref({ name: "", email: "", phone: "" });

function openEditUserForm(u) {
    editingUserId.value = u.id;
    userForm.value = {
        name: u.name,
        email: u.email,
        phone: u.phone || "",
    };
    userFormError.value = "";
    showUserForm.value = true;
}

async function saveUser() {
    if (!userForm.value.name || !userForm.value.email) {
        userFormError.value = "برجاء ملء الاسم والإيميل";
        return;
    }
    userSaving.value = true;
    userFormError.value = "";
    try {
        await axios.put(
            `/api/v1/admin/dashboard/users/${editingUserId.value}`,
            userForm.value
        );
        showUserForm.value = false;
        await fetchDashboardUsers();
    } catch (err) {
        userFormError.value =
            err?.response?.data?.message || "حصل خطأ أثناء الحفظ";
    } finally {
        userSaving.value = false;
    }
}

async function deleteUser(u) {
    if (
        !confirm(
            `هل أنت متأكد من حذف حساب "${u.name}" نهائيًا؟ سيتم حذف كل حجوزاته ونتائج اختباره أيضًا. لا يمكن التراجع عن هذا الإجراء.`
        )
    )
        return;

    u.deleting = true;
    try {
        await axios.delete(`/api/v1/admin/dashboard/users/${u.id}`);
        dashboardUsers.value = dashboardUsers.value.filter(
            (x) => x.id !== u.id
        );
    } catch (err) {
        alert(err?.response?.data?.message || "حصل خطأ أثناء الحذف");
        u.deleting = false;
    }
}

function levelBadgeClass(level) {
    return groupBadgeClass(level);
}

function formatDate(date) {
    if (!date) return "";
    return new Date(date).toLocaleDateString("ar-EG", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
}

// ══════════════════════════════════════════════
// INITIAL LOAD
// ══════════════════════════════════════════════
onMounted(() => {
    fetchBookings();
    fetchCourses();
});
</script>
