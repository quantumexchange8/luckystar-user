<script setup>
import AuthPanel from "@/Components/Auth/AuthPanel.vue";
import {isDark, toggleDarkMode} from "@/Composables/index.js";
import {IconMoon, IconSun} from "@tabler/icons-vue";
import Button from "primevue/button";
</script>

<template>
    <div class="flex h-screen">
        <!-- Left Pane -->
        <div class="hidden lg:flex items-center justify-center flex-1 bg-white dark:bg-surface-800 text-black dark:text-surface-600 transition-all duration-200">
            <AuthPanel />
        </div>

        <!-- Right Pane -->
        <div class="w-full bg-surface-100 dark:bg-surface-900 lg:w-1/2 flex items-center justify-center transition-all duration-200">
            <div class="flex items-center gap-2 self-stretch absolute top-5 right-5">
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

            <div class="max-w-md w-full p-5">
                <slot />
            </div>
        </div>
    </div>
</template>
