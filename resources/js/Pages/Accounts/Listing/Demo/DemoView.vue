<script setup>
import { Card } from 'primevue';
import DemoAction from './DemoAction.vue';
</script>

<template>
     <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 w-full gap-3 md:gap-5">
        <Card class="w-full">
            <template #content>
                <div class="flex flex-col gap-5 w-full self-stretch">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex flex-col items-center self-stretch">
                            <div class="flex items-center gap-2 self-stretch">
                                <div class="self-stretch flex items-center gap-4 truncate text-sm text-surface-600 dark:text-surface-500"> 
                                    <span class="font-bold text-lg">
                                        #12345
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-row gap-2 items-center self-stretch text-xs">
                                <span class="text-surface-500">{{ $t('public.name') }}:</span>
                                <span class="font-medium">{{ 'test' }}</span>
                            </div>
                        </div>
                        <DemoAction />
                    </div>
            
                    <div class="grid grid-cols-2 gap-2 w-full">
                        <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                            <span class="text-surface-500 w-20">{{ $t('public.balance') }}:</span>
                            <span class="font-medium">$123</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                            <span class="text-surface-500 w-20">{{ $t('public.equity') }}:</span>
                            <span class="font-medium">$456</span>
                        </div>

                        <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                            <span class="text-surface-500 w-20">{{ $t('public.credit') }}:</span>
                            <span class="font-medium">$78</span>
                        </div>

                       <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                            <span class="text-surface-500 w-20">{{ $t('public.leverage') }}:</span>
                            <span class="font-medium">1:1</span>
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>