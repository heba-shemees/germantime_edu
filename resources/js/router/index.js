import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history: createWebHistory(),
    scrollBehavior: () => ({ top: 0 }),
    routes: [
        {
            path: "/",
            name: "home",
            component: () => import("@/views/HomeView.vue"),
        },
        {
            path: "/start",
            name: "start",
            component: () => import("@/views/StartView.vue"),
        },
        {
            path: "/test",
            name: "test",
            component: () => import("@/views/TestView.vue"),
        },
        {
            path: "/test/result",
            name: "test-result",
            component: () => import("@/views/TestResultView.vue"),
        },
        {
            path: "/courses",
            name: "courses",
            component: () => import("@/views/CoursesView.vue"),
        },
        {
            path: "/courses/:level",
            name: "course-detail",
            component: () => import("@/views/CourseDetailView.vue"),
        },
        {
            path: "/private-lessons",
            name: "private-lessons",
            component: () => import("@/views/PrivateLessonsView.vue"),
        },
        {
            path: "/booking",
            name: "booking",
            component: () => import("@/views/BookingView.vue"),
        },
        {
            path: "/payment",
            name: "payment",
            component: () => import("@/views/PaymentView.vue"),
        },
        {
            path: "/about",
            name: "about",
            component: () => import("@/views/AboutView.vue"),
        },
        {
            path: "/testimonials",
            name: "testimonials",
            component: () => import("@/views/TestimonialsView.vue"),
        },
        {
            path: "/contact",
            name: "contact",
            component: () => import("@/views/ContactView.vue"),
        },
        {
            path: "/schedules",
            component: () => import("@/views/SchedulesView.vue"),
        },

        // ── Auth ──
        {
            path: "/login",
            name: "login",
            component: () => import("@/views/auth/LoginView.vue"),
        },
        {
            path: "/register",
            name: "register",
            component: () => import("@/views/auth/RegisterView.vue"),
        },
        {
            path: "/auth/google/callback",
            component: () => import("@/views/auth/GoogleCallbackView.vue"),
        },

        // ── User ──
        {
            path: "/profile",
            name: "profile",
            component: () => import("@/views/ProfileView.vue"),
            meta: { requiresAuth: true },
        },
        {
            path: "/my-bookings",
            name: "my-bookings",
            component: () => import("@/views/MyBookingsView.vue"),
            meta: { requiresAuth: true },
        },

        // ── Admin ──
        {
            path: "/admin",
            name: "admin",
            component: () => import("@/views/AdminView.vue"),
            meta: { requiresAdmin: true },
        },
        {
            path: "/admin/bookings",
            component: () => import("@/views/admin/BookingsAdminView.vue"),
            meta: { requiresAdmin: true },
        },
        {
            path: "/admin/schedules",
            component: () => import("@/views/admin/SchedulesAdminView.vue"),
            meta: { requiresAdmin: true },
        },

        {
            path: "/admin/test-questions",
            component: () => import("@/views/admin/TestQuestionsAdminView.vue"),
            meta: { requiresAdmin: true },
        },

        { path: "/:pathMatch(.*)*", redirect: "/" },
    ],
});

// ── Route Guards ──
router.beforeEach((to) => {
    const token = localStorage.getItem("token");
    const user = JSON.parse(localStorage.getItem("user") || "null");

    if (to.meta.requiresAuth && !token) {
        return { name: "login", query: { redirect: to.fullPath } };
    }

    if (to.meta.requiresAdmin && user?.role !== "admin") {
        return { name: "home" };
    }
});

router.afterEach((to) => {
    document.title = to.meta?.title || "German Time | تعلم الألماني";
});

export default router;
