<script  setup>
import { generalFormat } from '@/Composables/format';
import { IconCalendarCheck, IconCalendarCancel, IconUserStar, IconWallet, IconBan } from '@tabler/icons-vue';
import { Card, Button, Tag, Skeleton, Avatar } from 'primevue';
import { computed, ref, watch } from 'vue';
import SwitchStrategy from './Partial/SwitchStrategy.vue';
import InvestmentAction from './Partial/InvestmentAction.vue';
import InvestmentTopUp from './Partial/InvestmentTopUp.vue';
import InvestmentTerminate from './Partial/InvestmentTerminate.vue';
import dayjs from 'dayjs';
import Empty from '@/Components/Empty.vue';

const props = defineProps({
    subscribedStrategy: Array,
    isLoading: Boolean
});

// get oldest record to display join date
const strategy = computed(() => {
  if (!props.subscribedStrategy?.length) return {};
  
  return [...props.subscribedStrategy].sort(
    (a, b) => new Date(a.created_at) - new Date(b.created_at)
  )[0];
});


const {formatAmount, formatNameLabel} = generalFormat();

</script>

<template>
    <Card v-if="props.isLoading === true" class="w-full">
        <template #content>
            <div class="flex flex-col items-center gap-4 self-stretch">
                <div class="relative w-full flex items-center gap-4 self-stretch">
                    <div class="absolute top-0 right-0 text-xl font-semibold">
                        <div class="flex gap-2 items-center self-stretch">
                            <Skeleton shape="circle" size="2rem" class="mr-2"></Skeleton>
                            <Skeleton shape="circle" size="2rem"></Skeleton>
                        </div>
                    </div>

                    <Avatar
                        shape="circle"
                        class="min-w-10"
                    />

                    <div class="flex flex-col items-start">
                        <div class="self-stretch truncate text-surface-950 dark:text-white font-bold">
                            <Skeleton
                                width="7rem"
                                height="1rem"
                                class="my-1"
                                borderRadius="2rem"
                            ></Skeleton>
                        </div>
                        <div class="self-stretch truncate text-surface-500 text-sm">
                            <Skeleton
                                width="4rem"
                                height="1rem"
                                class="my-1"
                                borderRadius="2rem"
                            ></Skeleton>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 self-stretch">
                    <Skeleton
                        width="4rem"         
                        borderRadius="2rem"
                    ></Skeleton>
                    <Skeleton
                        width="4rem" 
                        borderRadius="2rem"
                    ></Skeleton>
                    <Skeleton
                        width="4rem"
                        borderRadius="2rem"
                    ></Skeleton>
                    <Skeleton
                        width="4rem"
                        borderRadius="2rem"
                    ></Skeleton>
                </div>

                <div class="py-2 flex justify-center items-center self-stretch border-y border-solid border-surface-200 dark:border-surface-700">
                    <div class="w-full flex flex-col items-center">
                        <Skeleton
                            width="4rem"
                            class="my-1"
                            borderRadius="2rem"
                        ></Skeleton>
                        
                        <div class="self-stretch text-surface-500 text-center text-xs">
                            {{ $t('public.capital') }}
                        </div>
                    </div>
                    <div class="w-full flex flex-col items-center">
                        <Skeleton
                            width="4rem"
                            class="my-1"
                            borderRadius="2rem"
                        ></Skeleton>
                        
                        <div class="self-stretch text-gray-500 text-center text-xs">
                            {{ $t('public.settlement') }}
                        </div>
                    </div>
                    <div class="w-full flex flex-col items-center">
                        <Skeleton
                            width="4rem"
                            class="my-1"
                            borderRadius="2rem"
                        ></Skeleton>
                        
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
                                <span class="text-blue-500"></span> 
                                <Skeleton
                                    width="4rem"
                                    borderRadius="2rem"
                                ></Skeleton>
                            </div>
                        </div>
                    </div>

                    <div class="py-1 flex items-center gap-3 w-full text-gray-500">
                        <div class="flex items-center gap-3 w-full">
                            <IconCalendarCheck size="20" stroke-width="1.25" />
                            <Skeleton
                                width="4rem"       
                                borderRadius="2rem"
                            ></Skeleton>
                        </div>
                    </div>

                    <div class="py-1 flex items-center gap-3 w-full text-gray-500">
                        <div class="flex items-center gap-3 w-full">
                            <IconCalendarCancel size="20" stroke-width="1.25" />
                            <Skeleton
                                width="4rem"
                                borderRadius="2rem"
                            ></Skeleton>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full">
                     <Button
                        type="button" 
                        class="w-full"
                        size="small"
                        severity="secondary"
                        :label="$t('public.top_up')"
                        disabled
                    >   
                        <template #icon>
                            <IconWallet size="20" stroke-width="1.5"/>
                        </template>
                    </Button>

                    <Button
                        type="button" 
                        class="w-full"
                        size="small"
                        severity="secondary"
                        :label="$t('public.terminate')"
                        disabled
                    >   
                        <template #icon>
                            <IconBan size="20" stroke-width="1.5"/>
                        </template>
                    </Button>
                </div>
            </div>
        </template>
    </Card>

    <Card v-else class="w-full">
        <template #content>
            <div class="flex flex-col items-center gap-4 self-stretch">
                <div class="relative w-full flex items-center gap-4 self-stretch">
                    <div class="absolute top-0 right-0 text-xl font-semibold">
                        <div class="flex gap-3 items-center self-stretch">
                            <SwitchStrategy />
                            
                            <InvestmentAction />
                        </div>
                    </div>

                    <Avatar
                        v-if="strategy.trading_master?.media"
                        :image="strategy.trading_master?.group_image"
                        shape="circle"
                        class="min-w-10"
                    />
                    <Avatar
                        v-else
                        :label="formatNameLabel(strategy.trading_master?.master_name)"
                        shape="circle"
                        class="min-w-10"
                    />

                    <div class="flex flex-col items-start">
                        <div class="self-stretch truncate text-surface-950 dark:text-white font-bold">
                            {{ strategy.trading_master?.master_name }}
                        </div>
                        <div class="self-stretch truncate text-surface-500 text-sm">
                            {{ strategy.trading_master?.trader_name }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 self-stretch">
                    <Tag severity="primary">
                        {{ $t(`public.${strategy.trading_master?.category}`) }}
                    </Tag>
                    <Tag severity="secondary">
                        {{ strategy.trading_master?.meta_login }}
                    </Tag>
                    <Tag severity="secondary" class="text-xs">
                        <div v-if="strategy.trading_master?.investment_period !== 0">
                            {{ strategy.trading_master?.investment_period }}
                            {{ $t(`public.${strategy.trading_master?.investment_period_type}`) }}
                        </div>
                        <div v-else>
                            {{ $t('public.lock_free') }}
                        </div>
                    </Tag>
                    <Tag severity="secondary" class="text-xs">
                        {{ formatAmount(strategy.trading_master?.sharing_profit, 0, '') + '%&nbsp;' + $t('public.profit') }}
                    </Tag>
                </div>

                <div class="py-2 flex justify-center items-center self-stretch border-y border-solid border-surface-200 dark:border-surface-700">
                    <div class="w-full flex flex-col items-center">
                        <div class="self-stretch text-surface-950 dark:text-white text-center font-semibold">
                            
                        </div>
                        <div class="self-stretch text-surface-500 text-center text-xs">
                            {{ $t('public.capital') }}
                        </div>
                    </div>
                    <div class="w-full flex flex-col items-center">
                        <div class="self-stretch text-surface-950 dark:text-white text-center font-semibold">
                            {{ strategy.trading_master?.settlement_period }} {{ $t(`public.${strategy.trading_master?.settlement_period_type}`) }} 
                        </div>
                        <div class="self-stretch text-gray-500 text-center text-xs">
                            {{ $t('public.settlement') }}
                        </div>
                    </div>
                    <div class="w-full flex flex-col items-center">
                        <div class="self-stretch text-center font-semibold">
                            <div
                                v-if="strategy.trading_master?.latest_profit !== 0"
                                :class="(strategy.trading_master?.latest_profit < 0) ? 'text-red-500' : 'text-green-500'"
                            >
                                {{ formatAmount(strategy.trading_master?.latest_profit ?? 0) }}
                            </div>
                            <div
                                v-else
                                class="text-surface-950 dark:text-white"
                            >
                                -
                            </div>
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
                                <span class="text-blue-500"></span> 
                                    {{ strategy.meta_login }}
                            </div>
                        </div>
                    </div>

                    <div class="py-1 flex items-center gap-3 w-full text-gray-500">
                        <div class="flex items-center gap-3 w-full">
                            <IconCalendarCheck size="20" stroke-width="1.25" />
                            <div
                                v-if="strategy.approval_at"
                                class="text-gray-950 dark:text-white text-sm font-medium"
                            >
                                <span class="text-blue-500 font-medium">
                                    {{ dayjs(strategy.approval_at).format('YYYY-MM-DD') }}
                                </span>
                                {{ $t('public.joined') }}
                            </div>

                            <div v-else>
                                <span>
                                    {{ $t('public.not_approve') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="py-1 flex items-center gap-3 w-full text-gray-500">
                        <div class="flex items-center gap-3 w-full">
                            <IconCalendarCancel size="20" stroke-width="1.25" />
                            <div 
                                v-if="strategy.settlement_at"
                                class="text-gray-950 dark:text-white text-sm font-medium"
                            >
                                <span class="text-primary font-medium">
                                    {{ dayjs(strategy.settlement_at).format('YYYY-MM-DD') }}
                                </span>
                                {{ $t('public.settlement_date') }}
                            </div>

                            <div v-else>
                                <span>
                                    {{ $t('public.no_settlement') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full">
                    <InvestmentTopUp 
                        v-if="strategy.trading_master?.can_top_up === 1"
                    />

                    <InvestmentTerminate 
                        v-if="strategy.trading_master?.can_terminate === 1"
                    />

                </div>
            </div>
        </template>
    </Card>
</template>