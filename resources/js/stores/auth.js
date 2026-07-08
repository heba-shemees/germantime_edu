import { ref, computed } from "vue";
import axios from "axios";

const user = ref(JSON.parse(localStorage.getItem("user") || "null"));
const token = ref(localStorage.getItem("token") || null);

if (token.value) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token.value}`;
}

export function useAuth() {
    const isLoggedIn = computed(() => !!token.value);
    const isAdmin = computed(() => user.value?.role === "admin");

    async function login(email, password) {
        const { data } = await axios.post("/api/v1/auth/login", {
            email,
            password,
        });
        setSession(data.user, data.token);
        return data.user;
    }

    async function register(name, email, password) {
        const { data } = await axios.post("/api/v1/auth/register", {
            name,
            email,
            password,
            password_confirmation: password,
        });
        setSession(data.user, data.token);
        return data.user;
    }

    function loginWithGoogle() {
        window.location.href = "/api/v1/auth/google/redirect";
    }

    async function logout() {
        try {
            await axios.post(
                "/api/v1/auth/logout",
                {},
                {
                    headers: { Authorization: `Bearer ${token.value}` },
                }
            );
        } catch {}
        clearSession();
    }

    async function fetchMe() {
        const { data } = await axios.get("/api/v1/auth/me", {
            headers: { Authorization: `Bearer ${token.value}` },
        });
        user.value = data.user;
        localStorage.setItem("user", JSON.stringify(data.user));
        return data.user;
    }

    function setSession(u, t) {
        user.value = u;
        token.value = t;
        localStorage.setItem("user", JSON.stringify(u));
        localStorage.setItem("token", t);
        axios.defaults.headers.common["Authorization"] = `Bearer ${t}`;
    }

    function clearSession() {
        user.value = null;
        token.value = null;
        localStorage.removeItem("user");
        localStorage.removeItem("token");
        delete axios.defaults.headers.common["Authorization"];
    }

    return {
        user,
        token,
        isLoggedIn,
        isAdmin,
        login,
        register,
        loginWithGoogle,
        logout,
        fetchMe,
        setSession,
        clearSession,
    };
}
