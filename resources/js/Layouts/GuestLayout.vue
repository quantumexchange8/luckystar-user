<script setup>
import AuthPanel from "@/Components/Auth/AuthPanel.vue";
import { isDark, toggleDarkMode } from "@/Composables/index.js";
import { IconMoon, IconSun } from "@tabler/icons-vue";
import Button from "primevue/button";
</script>

<template>
    <div class="flex h-screen">
        <!-- Left Pane -->
        <div class="hidden lg:flex items-center justify-center flex-1 bg-white dark:bg-surface-800 text-black dark:text-surface-600 transition-all duration-200">
            <AuthPanel />
        </div>

        <!-- Right Pane -->
        <div class="flex flex-col w-full lg:w-1/2 bg-surface-100 dark:bg-surface-900 transition-all duration-200 h-screen">
        
            <!-- Top Buttons -->
            <div class="flex items-center justify-end gap-2 p-5 shrink-0">
                <Button
                    severity="secondary"
                    outlined
                    aria-label="Mode"
                    size="small"
                    @click="() => { toggleDarkMode() }"
                >
                    <template #icon>
                        <IconSun v-if="!isDark" size="16" />
                        <IconMoon v-if="isDark" size="16" />
                    </template>
                </Button>

                <Button
                    severity="secondary"
                    outlined
                    aria-label="Mode"
                    size="small"
                    @click="() => { toggleDarkMode() }"
                >
                    <template #icon>
                        <IconSun v-if="!isDark" size="16" />
                        <IconMoon v-if="isDark" size="16" />
                    </template>
                </Button>
            </div>

            <!-- Scrollable Form Area -->
            <div class="flex-1 overflow-y-auto">
                <div class="min-h-full flex items-center justify-center p-4">
                    <div class="max-w-md w-full">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
