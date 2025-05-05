<script setup>
import { Card } from 'primevue';
import DemoAction from './DemoAction.vue';
</script>

<template>
     <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 w-full gap-3 md:gap-5">
        <Card class="w-full">
            <template #content>
                <div class="flex flex-col gap-5 w-full self-stretch">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 self-stretch">
                            <div class="self-stretch flex items-center gap-4 truncate text-sm text-surface-600 dark:text-surface-500">
                                <span class="font-bold text-lg">
                                    #12345
                                </span>
                            </div>
                        </div>
                        <DemoAction />
                    </div>
            
                    <div class="grid grid-cols-2 gap-2 w-full">
                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Balance:</span>
                            <span class="font-medium">$123</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Equity:</span>
                            <span class="font-medium">$456</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Credit:</span>
                            <span class="font-medium">$78</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch">
                            <span class="w-20">Leverage:</span>
                            <span class="font-medium">1:1</span>
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>