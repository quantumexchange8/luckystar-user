<script setup>
import { Card, Tag, Button, Skeleton } from 'primevue';
import IndividualAction from './IndividualAction.vue';
import Transfer from '../Partial/Transfer.vue';
import { onMounted, ref, watchEffect } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { generalFormat } from '@/Composables/format';
import EmptyData from '@/Components/EmptyData.vue';
import { IconDots } from '@tabler/icons-vue';
import AccountDeposit from '../Partial/AccountDeposit.vue';

const props = defineProps({
    accountCount: Number,
})

const isLoading = ref(false);
const first = ref(0);
const rows = ref(5);
const tradingAccount = ref([]);
const {formatAmount, formatRgbaColor} = generalFormat();

const lazyParams = ref({});

const loadLazyData = (event) => {
    isLoading.value = true;

    lazyParams.value = {
        ...lazyParams.value, first: event?.first || first.value, rows: event?.rows || rows.value
    };

    try {
        setTimeout(async () => {

            const params = {
                page: JSON.stringify(event?.page + 1),
                // sortOrder: event?.sortOrder,
                include: [],
                lazyEvent: JSON.stringify(lazyParams.value),
            };

            const url = route('get_trading_account_data', params);
            const response = await fetch(url);

            const results = await response.json();
            tradingAccount.value = results?.data?.data;
            isLoading.value = false;
        })
    } catch (e) {
        brokers.value = [];
        isLoading.value = false;
    }
}

onMounted(() => {
    lazyParams.value = {
        first: first.value, // Start from the first record
        rows: rows.value, // Rows per page
    };
    loadLazyData({ first: first.value, rows: rows.value });
});


watchEffect(() => {
    if (usePage().props.toast !== null) {
        loadLazyData();
    }
});
</script>

<template>
    <div
        v-if="props.accountCount === 0"
        class="w-full"
    >
        <EmptyData
            :title="$t('public.no_account_found')"
            :message="$t('public.no_account_found_caption')"
        />
    </div>

    <div
        v-else
        class="w-full"
    >
        <div v-if="isLoading">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 self-stretch">
                <Card
                    v-for="(account, index) in props.accountCount > 12 ? 12 : props.accountCount"
                    :key="index"
                >
                    <template #content>
                        <div class="flex flex-col gap-5 w-full self-stretch">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 self-stretch">
                                    <div class="self-stretch flex items-center gap-4 truncate text-sm text-surface-600 dark:text-surface-500">
                                        <span class="font-bold text-lg">
                                            <Skeleton height="1.5rem" width="6rem"></Skeleton>
                                        </span>
                                        <Skeleton height="1.5rem" width="6rem"></Skeleton>
                                    </div>
                                </div>
                                <IconDots size="20" stroke-width="1.5"/>
                            </div>

                            <div class="grid grid-cols-2 gap-2 w-full">
                                <div class="flex flex-row gap-1 items-center self-stretch">
                                    <span class="w-20"><Skeleton height="1.5rem" width="4rem"></Skeleton></span>
                                    <span class="font-medium"><Skeleton width="4rem"></Skeleton></span>
                                </div>

                                <div class="flex flex-row gap-1 items-center self-stretch">
                                    <span class="w-20"><Skeleton height="1.5rem" width="4rem"></Skeleton></span>
                                    <span class="font-medium"><Skeleton width="4rem"></Skeleton></span>
                                </div>

                                <div class="flex flex-row gap-1 items-center self-stretch">
                                    <span class="w-20"><Skeleton height="1.5rem" width="4rem"></Skeleton></span>
                                    <span class="font-medium"><Skeleton width="4rem"></Skeleton></span>
                                </div>

                                <div class="flex flex-row gap-1 items-center self-stretch">
                                    <span class="w-20"><Skeleton height="1.5rem" width="4rem"></Skeleton></span>
                                    <span class="font-medium"><Skeleton width="4rem"></Skeleton></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 w-full">
                                <Skeleton class="w-full" height="2.5rem"></Skeleton>
                                <Skeleton shape="circle" size="2.5rem"></Skeleton>
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
        </div>

        <div v-else-if="!tradingAccount.length">
            <EmptyData
                :title="$t('public.no_account_found')"
                :message="$t('public.no_account_found_caption')"
            />
        </div>

        <div v-else>
            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 w-full gap-3 md:gap-5">
                <Card
                    class="w-full"
                    v-for="(account, index) in tradingAccount"
                    :key="index"
                >
                    <template #content>
                        <div class="flex gap-3 items-center self-stretch relative">
                            <div
                                class="absolute -left-2 w-2.5 rounded-full h-full"
                                :style="{'backgroundColor': `#${account.account_type.color}`}"
                            ></div>
                            <div class="flex flex-col gap-5 w-full self-stretch pl-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex flex-col items-center self-stretch">
                                        <div class="flex items-center gap-2 self-stretch">
                                            <div class="self-stretch flex items-center gap-4 truncate text-sm text-surface-600 dark:text-surface-500">
                                        <span class="font-semibold text-lg">
                                            #{{ account.meta_login }}
                                        </span>
                                                <Tag
                                                    :style="{
                                                    backgroundColor: formatRgbaColor(account.account_type.color, 0.2),
                                                    color: `#${account.account_type.color}`,
                                                }"
                                                    :value="account.account_type.name"
                                                />
                                            </div>
                                        </div>
                                        <div class="flex flex-row gap-2 items-center self-stretch text-xs">
                                            <span class="text-surface-500">{{ $t('public.name') }}:</span>
                                            <span class="font-medium">{{ account.trading_user.name }}</span>
                                        </div>
                                    </div>
                                    <IndividualAction />
                                </div>

                                <div class="grid grid-cols-2 gap-2 w-full">
                                    <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                                        <span class="text-surface-500 w-20">Balance:</span>
                                        <span class="font-medium">{{ formatAmount(account.balance, 2)}}</span>
                                    </div>

                                    <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                                        <span class="text-surface-500 w-20">Equity:</span>
                                        <span class="font-medium">{{ formatAmount(account.equity, 2) }}</span>
                                    </div>

                                    <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                                        <span class="text-surface-500 w-20">Credit:</span>
                                        <span class="font-medium">{{ formatAmount(account.credit, 2) }}</span>
                                    </div>

                                    <div class="flex flex-row gap-1 items-center self-stretch text-xs">
                                        <span class="text-surface-500 w-20">Leverage:</span>
                                        <span class="font-medium">1:{{ account.margin_leverage }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 w-full">
                                    <AccountDeposit />

                                    <Transfer />
                                </div>
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
        </div>
    </div>
</template>
