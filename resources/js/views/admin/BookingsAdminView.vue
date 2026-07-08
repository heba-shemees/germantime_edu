<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        📋 الحجوزات
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">
                        إجمالي: {{ bookings.length }} حجز
                    </p>
                </div>
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

            <!-- Loading -->
            <div v-if="loading" class="space-y-4">
                <div
                    v-for="i in 4"
                    :key="i"
                    class="animate-pulse bg-white rounded-2xl h-32"
                ></div>
            </div>

            <!-- Empty -->
            <div
                v-else-if="filteredBookings.length === 0"
                class="text-center py-20"
            >
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500">مفيش حجوزات في هذه الحالة</p>
            </div>

            <!-- قائمة الحجوزات -->
            <div v-else class="space-y-4">
                <div
                    v-for="booking in filteredBookings"
                    :key="booking.id"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 transition-all hover:shadow-md"
                >
                    <div
                        class="flex flex-wrap gap-4 items-start justify-between"
                    >
                        <!-- بيانات الطالب -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-3 flex-wrap">
                                <span class="font-bold text-gray-900 text-lg">{{
                                    booking.name
                                }}</span>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold"
                                    :class="statusClass(booking.status)"
                                    >{{ statusLabel(booking.status) }}</span
                                >
                                <span class="text-xs text-gray-400 font-mono">{{
                                    booking.reference
                                }}</span>
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
                                <div>🕐 {{ timeAgo(booking.created_at) }}</div>
                                <div
                                    v-if="booking.notes"
                                    class="col-span-2 text-gray-500 italic"
                                >
                                    💬 {{ booking.notes }}
                                </div>
                            </div>

                            <!-- مؤقت الانتهاء لو pending -->
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

                        <!-- صورة الإيصال -->
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

                            <!-- أزرار الموافقة/الرفض -->
                            <div
                                v-if="booking.status === 'uploaded'"
                                class="flex gap-2"
                            >
                                <button
                                    @click="confirmBooking(booking)"
                                    :disabled="booking.loading"
                                    class="bg-green-500 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-green-600 transition-all disabled:opacity-50 hover:scale-105 active:scale-95"
                                >
                                    ✓ موافقة
                                </button>
                                <button
                                    @click="cancelBooking(booking)"
                                    :disabled="booking.loading"
                                    class="bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-600 transition-all disabled:opacity-50 hover:scale-105 active:scale-95"
                                >
                                    ✕ رفض
                                </button>
                            </div>

                            <!-- زر واتساب لو confirmed -->
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

        <!-- Modal صورة الإيصال -->
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
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

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

// ── جلب الحجوزات ──────────────────────────────
async function fetchBookings() {
    console.log("fetchBookings started");

    loading.value = true;
    try {
        const response = await axios.get("/api/v1/admin/bookings");
        console.log(response);

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

// ── فلترة ─────────────────────────────────────
const filteredBookings = computed(() =>
    activeFilter.value === "all"
        ? bookings.value
        : bookings.value.filter((b) => b.status === activeFilter.value)
);

function countByStatus(status) {
    if (status === "all") return bookings.value.length;
    return bookings.value.filter((b) => b.status === status).length;
}

// ── موافقة ────────────────────────────────────
async function confirmBooking(booking) {
    if (!confirm(`تأكيد موافقة حجز ${booking.name}؟`)) return;
    booking.loading = true;
    try {
        await axios.patch(`/api/v1/admin/bookings/${booking.id}/confirm`);
        booking.status = "confirmed";
    } catch (err) {
        alert("حصل خطأ، حاول تاني");
    } finally {
        booking.loading = false;
    }
}

// ── رفض ───────────────────────────────────────
async function cancelBooking(booking) {
    if (!confirm(`رفض وإلغاء حجز ${booking.name}؟`)) return;
    booking.loading = true;
    try {
        await axios.patch(`/api/v1/admin/bookings/${booking.id}/cancel`);
        booking.status = "cancelled";
    } catch (err) {
        alert("حصل خطأ، حاول تاني");
    } finally {
        booking.loading = false;
    }
}

// ── واتساب ────────────────────────────────────
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

onMounted(fetchBookings);
</script>
