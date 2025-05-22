<script  setup>
import { generalFormat } from '@/Composables/format';
import { IconCalendarCheck, IconCalendarCancel, IconUserStar, IconBan, IconWallet } from '@tabler/icons-vue';
import { Card, Button, Tag, Skeleton, Avatar } from 'primevue';
import { computed, ref, watch } from 'vue';
import SwitchStrategy from './Partial/SwitchStrategy.vue';
import InvestmentAction from './Partial/InvestmentAction.vue';

const props = defineProps({
   subscribedStrategy: Array,
});

console.log('view',props.subscribedStrategy)

const {formatAmount, formatNameLabel} = generalFormat();

const totalBalance = computed(() => {
  const subscriptions = props.subscribedStrategy[0]?.trading_subscription || [];

  return subscriptions.reduce((sum, sub) => {
    const balance = parseFloat(sub.subscription_amount) || 0;
    return sum + balance;
  }, 0);
});

</script>

<template>
        <Card v-if="props.subscribedStrategy.length > 0" class="w-full">
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
                            v-if="props.subscribedStrategy[0]?.media"
                            :image="props.subscribedStrategy[0].group_image"
                            shape="circle"
                            class="min-w-10"
                        />
                        <Avatar
                            v-else
                            :label="formatNameLabel(props.subscribedStrategy[0].master_name)"
                            shape="circle"
                            class="min-w-10"
                        />

                        <div class="flex flex-col items-start">
                            <div class="self-stretch truncate text-surface-950 dark:text-white font-bold">
                                {{ props.subscribedStrategy[0].master_name }}
                            </div>
                            <div class="self-stretch truncate text-surface-500 text-sm">
                                {{ props.subscribedStrategy[0].trader_name }}
                            </div>
                        </div>
                    </div>


                    <div class="flex flex-wrap items-center gap-2 self-stretch">
                        <Tag severity="primary">
                            {{ $t(`public.${props.subscribedStrategy[0].category}`) }}
                        </Tag>
                        <Tag severity="secondary">
                             {{ props.subscribedStrategy[0].meta_login }}
                        </Tag>
                    </div>

                    <div class="py-2 flex justify-center items-center self-stretch border-y border-solid border-surface-200 dark:border-surface-700">
                        <div class="w-full flex flex-col items-center">
                            <div class="self-stretch text-surface-950 dark:text-white text-center font-semibold">
                                {{ formatAmount(totalBalance, 2) }}
                            </div>
                            <div class="self-stretch text-surface-500 text-center text-xs">
                                {{ $t('public.balance') }}
                            </div>
                        </div>
                        <div class="w-full flex flex-col items-center">
                            <div class="self-stretch text-surface-950 dark:text-white text-center font-semibold">
                                {{ props.subscribedStrategy[0].settlement_period }} {{ $t(`public.${props.subscribedStrategy[0].settlement_period_type}`) }} 
                            </div>
                            <div class="self-stretch text-gray-500 text-center text-xs">
                                {{ $t('public.settlement') }}
                            </div>
                        </div>
                        <div class="w-full flex flex-col items-center">
                            <div class="self-stretch text-center font-semibold">
                                <div
                                    v-if="props.subscribedStrategy[0].latest_profit !== 0"
                                    :class="(props.subscribedStrategy[0].latest_profit < 0) ? 'text-red-500' : 'text-green-500'"
                                >
                                    {{ formatAmount(props.subscribedStrategy[0].latest_profit ?? 0) }}
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

                    <!-- <div class="flex flex-col gap-1 w-full">
                        <div class="py-1 flex items-center gap-3 w-full text-gray-500">
                            <div class="flex items-center gap-3 w-full">
                                <IconUserStar size="20" stroke-width="1.25" />
                                <div class="text-gray-950 dark:text-white text-sm font-medium">
                                   
                                </div>
                            </div>
                        </div>
                      
                        <div class="py-1 flex items-center gap-3 w-full text-gray-500">
                            <div class="flex items-center gap-3 w-full">
                                <IconCalendarCheck size="20" stroke-width="1.25" />
                                <div class="text-gray-950 dark:text-white text-sm font-medium">
                                    <span class="text-blue-500"></span> 
                                    {{ $t('public.joined') }}
                                </div>
                            </div>
                        </div>

                        <div class="py-1 flex items-center gap-3 w-full text-gray-500">
                            <div class="flex items-center gap-3 w-full">
                                <IconCalendarCancel size="20" stroke-width="1.25" />
                                <div class="text-gray-950 dark:text-white text-sm font-medium">
                                    <span class="text-red-500 ml-1"></span>
                                    {{ $t('public.terminated') }}
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="flex items-center gap-3 w-full">
                        <Button
                            v-if="props.subscribedStrategy[0].can_top_up === 1"
                            type="button" 
                            class="w-full"
                            size="small"
                            :label="$t('public.top_up')"
                        >   
                            <template #icon>
                                <IconWallet size="20" stroke-width="1.5"/>
                            </template>
                        </Button>

                        <Button
                            v-if="props.subscribedStrategy[0].can_terminate === 1"
                            type="button" 
                            class="w-full"
                            size="small"
                            severity="danger"
                            :label="$t('public.terminate')"
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
                            <div class="flex gap-2 items-center self-stretch">
                                <Skeleton shape="circle" size="2rem" class="mr-2"></Skeleton>
                                <Skeleton shape="circle" size="2rem" class="mr-2"></Skeleton>
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
                                    class="my-1"
                                    borderRadius="2rem"
                                ></Skeleton>
                            </div>
                            <div class="self-stretch truncate text-surface-500 text-sm">
                                  <Skeleton
                                    width="4rem"
                                    class="my-1"
                                    borderRadius="2rem"
                                ></Skeleton>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 self-stretch">
                         <Skeleton
                            width="4rem"
                            class="my-1"
                            borderRadius="2rem"
                        ></Skeleton>
                        <Skeleton
                            width="4rem"
                            class="my-1"
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
                                {{ $t('public.balance') }}
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

                    <div class="flex items-center gap-3 w-full">
                         <Skeleton
                            class="w-full"
                            borderRadius="2rem"
                            height="2rem"
                        ></Skeleton>

                         <Skeleton
                            class="w-full"
                            borderRadius="2rem"
                            height="2rem"
                        ></Skeleton>
                    </div>
                </div>
            </template>
        </Card>
</template>