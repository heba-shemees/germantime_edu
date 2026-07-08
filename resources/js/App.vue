<template>
    <div class="min-h-screen flex flex-col">
        <AppNavbar />
        <main class="flex-1">
            <RouterView v-slot="{ Component, route }">
                <Transition name="page" mode="out-in">
                    <component :is="Component" :key="route.path" />
                </Transition>
            </RouterView>
        </main>
        <AppFooter />
    </div>
</template>

<script setup>
import AppNavbar from "@/components/layout/AppNavbar.vue";
import AppFooter from "@/components/layout/AppFooter.vue";
</script>

<style>
.page-enter-active,
.page-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.page-enter-from {
    opacity: 0;
    transform: translateY(8px);
}
.page-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}
</style>
