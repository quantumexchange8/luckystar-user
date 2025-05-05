<script setup>
import {  IconAdjustments, IconPremiumRights, IconSearch, IconUserDollar, IconXboxX } from '@tabler/icons-vue';
import { IconField, InputIcon, InputText, Button, Select, Card, Tag, Paginator } from 'primevue';
import { ref } from 'vue';
import JoinPamm from './Partial/JoinPamm.vue';

const selectedSort = ref('latest');

const sortOptions = ref([
    'latest',
    'largest_fund',
    'most_investors',
    'my_joining',
]);

const dummyData = ref([
    {
        strategyName: 'Growth Strategy A',
        username: 'trader_alpha',
        totalGain: 5.2,
        monthlyGain: 1.1,
        investors: 120,
        fundSize: 84263.00,
    },
    {
        strategyName: 'Value Strategy B',
        username: 'investor_beta',
        totalGain: 10.0,
        monthlyGain: 2.4,
        investors: 76,
        fundSize: 123400.00,
    },
    {
        strategyName: 'Momentum Strategy C',
        username: 'swing_chad',
        totalGain: -2.5,
        monthlyGain: -0.8,
        investors: 54,
        fundSize: 29400.00,
    },
    {
        strategyName: 'Income Strategy D',
        username: 'dividude',
        totalGain: 8.5,
        monthlyGain: 1.5,
        investors: 82,
        fundSize: 384263.00,
    }
]);

</script>

<template>
   <div class="flex flex-col md:flex-row gap-3 items-center self-stretch">
        <div class="relative w-full md:w-60">
            <IconField>
                <InputIcon>
                    <IconSearch :size="16" stroke-width="1.5" />
                </InputIcon>
                <InputText
                    :placeholder="$t('public.search_keyword')"
                    type="text"
                    class="block w-full pl-10 pr-10"
                />
                <!-- Clear filter button -->
                <div
                    class="absolute top-1/2 -translate-y-1/2 right-4 text-surface-300 hover:text-surface-400 select-none cursor-pointer"
                    @click="clearFilterGlobal"
                >
                    <IconXboxX aria-hidden="true" :size="15" />
                </div>
            </IconField>

        </div>

        <div class="w-full flex justify-between items-center self-stretch gap-3">
             <!-- filter button -->
            <Button
                class="w-full md:w-28 flex items-center gap-2 dark:text-white text-surface bg-surface-0 dark:bg-surface-800 border-surface-300 dark:border-surface-700 enabled:hover:bg-white dark:hover:bg-surface-800 enabled:hover:border-surface-500 dark:hover:border-surface-600"
            >
                <IconAdjustments :size="20" stroke-width="1.5"/>
                {{ $t('public.filter') }}
            </Button>

            <Select
                class="w-full md:w-56"
                v-model="selectedSort"
                :options="sortOptions"
                optionLabel="name"
                :placeholder="'Sort'"
            >
                <template #value="slotProps">
                    <div v-if="slotProps.value" class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <div>{{ $t(`public.${slotProps.value}`) }}</div>
                        </div>
                    </div>
                </template>
                <template #option="slotProps">
                    {{ $t(`public.${slotProps.option}`) }}
                </template>
            </Select>
        </div>
    </div>

    <div class="w-full">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 self-stretch pb-5">
            <Card v-for="(strategy, index) in dummyData" :key="index" class="w-full">
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

                        <div class="flex items-end justify-between self-stretch">
                            <div class="flex flex-col items-center gap-1 self-stretch w-full">
                                <div class="py-1 flex items-center gap-3 self-stretch w-full text-gray-500">
                                    <IconUserDollar size="20" stroke-width="1.25" />
                                    <div class="text-gray-950 dark:text-white text-sm font-medium">
                                        {{ strategy.investors }} {{ $t('public.investor') }}
                                    </div>
                                </div>
                                <div class="py-1 flex items-center gap-3 self-stretch text-gray-500">
                                    <IconPremiumRights size="20" stroke-width="1.25" />
                                    <div class="text-gray-950 dark:text-white text-sm font-medium">
                                       {{ $t('public.total_fund_size') }}
                                       <span class="text-primary">${{ strategy.fundSize.toLocaleString() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 w-full">
                            <JoinPamm />
                        </div>
                    </div>
                </template>
            </Card>
        </div>

        <Paginator
            :rows="10" 
            :totalRecords="120" 
            :rowsPerPageOptions="[10, 20, 30]"
        />
    </div>
</template>