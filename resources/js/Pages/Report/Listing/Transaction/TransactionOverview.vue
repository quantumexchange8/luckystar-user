<script setup>
import { IconCloudDollar, IconCurrencyDollar, IconCurrencyDollarOff } from '@tabler/icons-vue';
import { Card, Skeleton } from 'primevue';
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 w-full gap-3 md:gap-5">
        <Card>
            <template #content>
                <div class="flex justify-between items-center">
                    <div class="flex flex-col items-start gap-3">
                        <div class="font-medium text-surface-500">
                            {{ $t("public.success_amount") }}
                        </div>
                        <div class="text-xl font-semibold md:text-3xl">
                            <!-- <div v-if="isLoading">
                                <Skeleton width="5rem" height="2rem"></Skeleton>
                            </div> -->

                            <div>
                                $213
                            </div>

                        </div>
                    </div>
                    <div class="flex items-center justify-center rounded-full bg-green-100 dark:bg-green-900/40 w-[72px] h-[72px]">
                        <div class="flex items-center justify-center rounded-full bg-green-200 dark:bg-green-700 w-14 h-14 text-green-600 dark:text-green-300">
                            <IconCurrencyDollar size="36" stroke-width="1.5" />
                        </div>
                    </div>
                </div>
            </template>
        </Card>

        <Card>
            <template #content>
                <div class="flex justify-between items-center">
                    <div class="flex flex-col items-start gap-3">
                        <div class="font-medium text-surface-500">
                            {{ $t("public.reject_amount") }}
                        </div>
                        <div class="text-xl font-semibold md:text-3xl">
                            <!-- <div v-if="isLoading">
                                <Skeleton width="5rem" height="2rem"></Skeleton>
                            </div> -->
                            
                            <div>
                                $444
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900/40 w-[72px] h-[72px]">
                        <div class="flex items-center justify-center rounded-full bg-red-200 dark:bg-red-700 w-14 h-14 text-red-600 dark:text-red-300">
                            <IconCurrencyDollarOff size="36" stroke-width="1.5" />
                        </div>
                    </div>
                </div>
            </template>
        </Card>

        <Card class="col-span-1 md:col-span-2 lg:col-span-1">
            <template #content>
                <div class="flex justify-between items-center">
                    <div class="flex flex-col items-start gap-3">
                        <div class="font-medium text-surface-500">
                            {{ $t("public.total_amount") }}
                        </div>
                        <div class="text-xl font-semibold md:text-3xl">
                            <!-- <div v-if="isLoading">
                                <Skeleton width="5rem" height="2rem"></Skeleton>
                            </div> -->
                            
                            <div>
                                $807
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/40 w-[72px] h-[72px]">
                        <div class="flex items-center justify-center rounded-full bg-blue-200 dark:bg-blue-700 w-14 h-14 text-blue-600 dark:text-blue-300">
                            <IconCloudDollar size="36" stroke-width="1.5" />
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>