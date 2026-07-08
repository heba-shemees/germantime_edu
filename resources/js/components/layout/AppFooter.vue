<template>
    <footer class="bg-gray-900 text-gray-300 pt-12 pb-6 mt-20">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
                <div>
                    <div
                        class="flex items-center gap-2 font-bold text-xl text-white mb-3"
                    >
                        <span class="text-2xl">🇩🇪</span>
                        <span>German Time</span>
                    </div>
                    <p class="text-sm leading-relaxed text-gray-400">
                        {{ t("footer_desc") }}
                    </p>
                    <a
                        :href="whatsappLink"
                        target="_blank"
                        class="text-green-400 hover:text-green-300 font-medium text-sm mt-4 block"
                    >
                        {{ t("whatsapp_direct") }}
                    </a>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">
                        {{ t("footer_links") }}
                    </h4>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <RouterLink
                                to="/courses"
                                class="hover:text-white transition-colors"
                                >{{ t("nav_courses") }}</RouterLink
                            >
                        </li>
                        <li>
                            <RouterLink
                                to="/private-lessons"
                                class="hover:text-white transition-colors"
                                >{{ t("nav_private") }}</RouterLink
                            >
                        </li>
                        <li>
                            <RouterLink
                                to="/test"
                                class="hover:text-white transition-colors"
                                >{{ t("nav_test") }}</RouterLink
                            >
                        </li>
                        <li>
                            <RouterLink
                                to="/about"
                                class="hover:text-white transition-colors"
                                >{{ t("nav_about") }}</RouterLink
                            >
                        </li>
                        <li>
                            <RouterLink
                                to="/testimonials"
                                class="hover:text-white transition-colors"
                                >{{ t("testimonials_title") }}</RouterLink
                            >
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">
                        {{ t("footer_contact") }}
                    </h4>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a
                                :href="whatsappLink"
                                target="_blank"
                                class="hover:text-white transition-colors flex items-center gap-2"
                                ><span>📱</span> WhatsApp</a
                            >
                        </li>
                        <li>
                            <a
                                href="https://instagram.com"
                                target="_blank"
                                class="hover:text-white transition-colors flex items-center gap-2"
                                ><span>📸</span> Instagram</a
                            >
                        </li>
                        <li>
                            <a
                                href="https://tiktok.com"
                                target="_blank"
                                class="hover:text-white transition-colors flex items-center gap-2"
                                ><span>🎵</span> TikTok</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="border-t border-gray-800 pt-6 text-center text-xs text-gray-500"
            >
                © {{ new Date().getFullYear() }} German Time ·
                {{ t("footer_rights") }}
            </div>
        </div>
    </footer>
</template>
<script setup>
import { useLang } from "@/i18n";
const { t } = useLang();
const whatsappLink = "https://wa.me/201000000000";
</script>
