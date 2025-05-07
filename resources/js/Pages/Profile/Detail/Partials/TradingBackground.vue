<script setup>
import {Button, Card} from "primevue";
import {IconAlertSquareRoundedFilled, IconEdit} from "@tabler/icons-vue";

const props = defineProps({
    user: Object,
})
</script>

<template>
    <Card class="w-full">
        <template #title>
            <div class="flex gap-5 items-center justify-between">
                {{ $t('public.trading_background') }}
                <Button
                    type="button"
                    icon="IconEdit"
                    severity="secondary"
                    rounded
                    text
                >
                    <template #icon>
                        <IconEdit size="16" stroke-width="1.5" />
                    </template>
                </Button>
            </div>
        </template>

        <template #content>
            <div class="flex flex-col gap-3 items-center">
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.trading_experience') }}
                    </div>
                    <div>
                        >1 {{ $t('public.years') }}
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.types_of_products_traded') }}
                    </div>
                    <div>
                        Forex, Crypto, Stocks
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.number_of_trades_per_month') }}
                    </div>
                    <div>
                        1-5
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.familiarity_with_trading_concepts') }}
                    </div>
                    <div>
                        Leverage, Margin, Stop Loss
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.experience_in_trading_platforms') }}
                    </div>
                    <div>
                        MT4, MT5, cTrader
                    </div>
                </div>
            </div>
        </template>
    </Card>
</template>
