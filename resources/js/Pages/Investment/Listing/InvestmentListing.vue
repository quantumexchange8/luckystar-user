<script setup>
import { IconCalendarCheck, IconCalendarCancel, IconUserStar } from '@tabler/icons-vue';
import { Card, Button } from 'primevue';
import { ref } from 'vue';

const dummyData = ref([
    {
        strategyName: 'Growth Strategy A',
        username: 'trader_alpha',
        totalGain: 5.2,
        monthlyGain: 1.1,
        account_no: '123455',
        joined_date: '2025-04-30',
        terminated_date: '2025-12-31',
    },
    {
        strategyName: 'Value Strategy B',
        username: 'investor_beta',
        totalGain: 10.0,
        monthlyGain: 2.4,
        account_no: '123455',
        joined_date: '2025-04-30',
        terminated_date: '2025-12-31',
    },
    // {
    //     strategyName: 'Momentum Strategy C',
    //     username: 'swing_chad',
    //     totalGain: -2.5,
    //     monthlyGain: -0.8,
    //     account_no: '123455',
    //     joined_date: '2025-04-30',
    //     terminated_date: '2025-12-31',
    // },
]);
</script>

<template>
    <div class="flex gap-5 items-center overflow-x-auto w-full">
        <Card v-for="(strategy, index) in dummyData" :key="index"    class="flex flex-col gap-3 flex-shrink-0 min-w-80 md:min-w-[480px] lg:min-w-[709px]">
            <template #content>
                <div class="flex flex-col items-center gap-4 self-stretch">
                    <div class="w-full flex items-center gap-4 self-stretch">
                        <img
                            class="object-cover w-11 h-11 rounded-full"
                            src="https://img.freepik.com/free-icon/user_318-159711.jpg"
                            alt="masterPic"
                        />
                        <div class="flex flex-col items-start">
                            <div class="self-stretch truncate text-surface-950 dark:text-white font-bold">
                                {{ strategy.strategyName }}
                            </div>
                            <div class="self-stretch truncate text-surface-500 text-sm">
                                {{ strategy.username }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 self-stretch">
                        <Tag severity="primary">
                            $1,000
                        </Tag>
                        <Tag severity="secondary">
                            12 months
                        </Tag>
                        <Tag severity="secondary">
                            10% fee
                        </Tag>
                    </div>

                    <div class="py-2 flex justify-center items-center self-stretch border-y border-solid border-surface-200 dark:border-surface-700">
                        <div class="w-full flex flex-col items-center">
                            <div class="self-stretch text-surface-950 dark:text-white text-center font-semibold">
                                {{ strategy.totalGain }}%
                            </div>
                            <div class="self-stretch text-surface-500 text-center text-xs">
                                {{ $t('public.total_gain') }}
                            </div>
                        </div>
                        <div class="w-full flex flex-col items-center">
                            <div class="self-stretch text-surface-950 dark:text-white text-center font-semibold">
                                {{ strategy.monthlyGain }}%
                            </div>
                            <div class="self-stretch text-gray-500 text-center text-xs">
                                {{ $t('public.monthly_gain') }}
                            </div>
                        </div>
                        <div class="w-full flex flex-col items-center">
                            <div class="self-stretch text-center font-semibold">
                                -
                            </div>
                            <div class="self-stretch text-gray-500 text-center text-xs">
                                {{ $t('public.latest') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1 w-full">
                        <div class="py-1 flex items-center gap-3 w-full text-gray-500">
                            <div class="flex items-center gap-3 w-full">
                                <IconUserStar size="20" stroke-width="1.25" />
                                <div class="text-gray-950 dark:text-white text-sm font-medium">
                                    {{ strategy.account_no }}
                                </div>
                            </div>
                        </div>
                      
                        <div class="py-1 flex items-center gap-3 w-full text-gray-500">
                            <div class="flex items-center gap-3 w-full">
                                <IconCalendarCheck size="20" stroke-width="1.25" />
                                <div class="text-gray-950 dark:text-white text-sm font-medium">
                                    <span class="text-blue-500">{{ strategy.joined_date }}</span> 
                                    {{ $t('public.joined') }}
                                </div>
                            </div>
                        </div>

                        <div class="py-1 flex items-center gap-3 w-full text-gray-500">
                            <div class="flex items-center gap-3 w-full">
                                <IconCalendarCancel size="20" stroke-width="1.25" />
                                <div class="text-gray-950 dark:text-white text-sm font-medium">
                                    <span class="text-red-500 ml-1">{{ strategy.terminated_date }}</span>
                                    {{ $t('public.terminated') }}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="flex items-center gap-3 w-full">
                        <Button
                            type="button" 
                            class="w-full"
                            size="small"
                            :label="$t('public.view_strategy')"
                        />
                    </div>
                </div>
            </template>
        </Card>
        
    </div>
</template>