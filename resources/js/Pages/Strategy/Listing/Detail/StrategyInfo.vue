<script setup>
import { IconCoinYen, IconHourglassHigh, IconTargetArrow } from '@tabler/icons-vue';
import { Card, Button } from 'primevue';
</script>

<template>
    <div class="flex flex-col md:flex-row gap-5 w-full">
        <Card class="w-full">
            <template #content>
                <div class="flex flex-col items-center gap-8">
                    <div class="flex flex-col items-center gap-4 w-full">
                        <img 
                            class="object-cover w-11 h-11 rounded-full"
                            src="https://img.freepik.com/free-icon/user_318-159711.jpg"
                            alt="masterPic"
                        />
    
                        <div class="flex flex-col gap-1 items-center">
                            <span class="font-semibold">
                                Strategy Name
                            </span>
                            <span>
                                Strategy ID
                            </span>
                        </div>
    
                        <Button
                            class="w-full"
                            size="small"
                        >
                            {{ $t('public.join_pamm') }}
                        </Button>
                    </div>
    
                    <div class="grid grid-cols-2 gap-5 w-full">
                        <div class="flex flex-col items-center gap-1 self-stretch">
                            <span class="text-xs">
                                {{ $t('public.total_gain') }}
                            </span>
    
                            <span class="text-lg font-semibold">
                                10%
                            </span>
                        </div>
    
    
                        <div class="flex flex-col items-center gap-1 self-stretch">
                            <span class="text-xs">
                                {{ $t('public.monthly_gain') }}
                            </span>
    
                            <span class="text-lg font-semibold">
                                30%
                            </span>
                        </div>
    
                        <div class="flex flex-col items-center gap-1 self-stretch">
                            <span class="text-xs">
                                {{ $t('public.latest') }}
                            </span>
    
                            <span class="text-lg font-semibold">
                                2%
                            </span>
                        </div>
    
                        <div class="flex flex-col items-center gap-1 self-stretch">
                            <span class="text-xs">
                                {{ $t('public.total_investor') }}
                            </span>
    
                            <span class="text-lg font-semibold">
                                200
                            </span>
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    
        <Card class="w-full">
            <template #content>
                <div class="flex flex-col items-center gap-8">
                    <div class="flex flex-col items-start gap-4 w-full">
                        <span class="font-bold">
                            Fees and Conditions
                        </span>
                    </div>
    
                    <div class="grid grid-cols-2 gap-5 w-full">
                        <div class="flex flex-col justify-center items-center gap-3 flex-1">
                            <div class="flex flex-col items-center gap-1 self-stretch">
                                <div class="flex items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/40 w-[72px] h-[72px]">
                                    <div class="flex items-center justify-center rounded-full bg-yellow-200 dark:bg-yellow-700 w-14 h-14 text-yellow-600 dark:text-yellow-200">
                                        <IconCoinYen size="36" stroke-width="1.5" />
                                    </div>
                                </div>
                            </div>
    
                            <div class="flex flex-col items-center gap-1 self-stretch">
                                <span class="text-xs">
                                    {{ $t('public.minimum_investment') }}
                                </span>
        
                                <span class="text-lg font-semibold">
                                    $1,000
                                </span>
                            </div>
                        </div>
    
                        <div class="flex flex-col justify-center items-center gap-3 flex-1">
                            <div class="flex flex-col items-center gap-1 self-stretch">
                                <div class="flex items-center justify-center rounded-full bg-surface-100 dark:bg-surface-800 w-[72px] h-[72px]">
                                    <div class="flex items-center justify-center rounded-full bg-surface-200 dark:bg-surface-700 w-14 h-14 text-surface-600 dark:text-surface-200">
                                        <IconTargetArrow size="36" stroke-width="1.5" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-center gap-1 self-stretch">
                                <span class="text-xs">
                                    {{ $t('public.performance_fee') }}
                                </span>
        
                                <span class="text-lg font-semibold">
                                    20%
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-span-2 flex flex-col items-center gap-3 self-stretch">
                            <div class="flex flex-col items-center gap-1 self-stretch">
                                <div class="flex items-center justify-center rounded-full bg-green-100 dark:bg-green-900/40 w-[72px] h-[72px]">
                                    <div class="flex items-center justify-center rounded-full bg-green-200 dark:bg-green-700 w-14 h-14 text-green-600 dark:text-green-200">
                                        <IconHourglassHigh size="36" stroke-width="1.5" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-center gap-1 self-stretch">
                                <span class="text-xs">
                                    {{ $t('public.minimum_investment_period') }}
                                </span>
        
                                <span class="text-lg font-semibold">
                                    12 months
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>