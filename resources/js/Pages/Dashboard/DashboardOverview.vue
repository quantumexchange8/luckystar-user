<script setup>
import {
    Card,
    Button
} from "primevue";
import {IconArrowUpRight} from "@tabler/icons-vue";
import {generalFormat} from "@/Composables/format.js";

const overviews = [
    {
        label: "total_investment_capital",
        amount: 10000,
        trend: "7.4%",
        itemClass: "bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-300"
    },
    {
        label: "group_investment_capital",
        amount: 100000,
        trend: "5.4%",
        itemClass: "bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300"
    },
    {
        label: "total_profit",
        amount: 400,
        trend: "1.5%",
        itemClass: "bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-300"
    },
    {
        label: "total_bonus",
        amount: 200,
        trend: "100%",
        itemClass: "bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-300"
    }
];

const {formatAmount} = generalFormat();
</script>

<template>
    <div class="grid gap-5 w-full grid-cols-2">
        <Card
            v-for="item in overviews"
            :class="item.itemClass"
        >
            <template #content>
                <div class="flex flex-col items-start gap-3 w-full">
                    <div class="flex items-center gap-3 justify-between w-full">
                        <span class="text-xs md:text-sm flex items-center self-stretch text-surface-950 dark:text-white">{{ $t(`public.${item.label}`) }}</span>
                        <Button
                            severity="secondary"
                            rounded
                            icon="IconArrowUpRight"
                            class="!min-w-10"
                        >
                            <template #icon>
                                <IconArrowUpRight size="20" stroke-width="1.5" />
                            </template>
                        </Button>
                    </div>
                    <div class="text-lg md:text-3xl font-medium text-surface-950 dark:text-white">
                        {{ formatAmount(item.amount) }}
                    </div>
                    <div class="text-xs">
                        {{ item.trend }} vs last month
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>
