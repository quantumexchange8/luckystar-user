<script setup>
import {
    Button,
} from "primevue";
import {generalFormat} from "@/Composables/format.js";

defineProps({
    wallets: Object,
});

const {formatAmount} = generalFormat();
</script>

<template>
    <div
        v-for="wallet in wallets"
        class="py-3 px-5 border border-primary w-full rounded-md self-stretch flex flex-col items-start gap-3 shadow-toast"
    >
        <div class="flex items-center justify-between gap-3 self-stretch">
            <div class="flex flex-col">
                <div class="text-sm md:text-base font-medium text-surface-950 dark:text-white">
                    {{ $t(`public.${wallet.type}`) }}
                </div>
                <div class="text-xs text-surface-400">
                    {{ wallet.address }}
                </div>
            </div>

            <div class="w-10 h-10 bg-primary-100 rounded-full grow-0 shrink-0">

            </div>
        </div>
        <div class="text-lg font-medium text-surface-950 dark:text-white">
            {{ formatAmount(wallet.balance) }}
        </div>
        <div class="flex gap-3 items-center self-stretch">
            <Button
                type="button"
                size="small"
                class="w-full"
                :label="$t('public.deposit')"
            />
            <Button
                type="button"
                severity="secondary"
                size="small"
                class="w-full"
                :label="$t('public.action')"
            />
        </div>
    </div>
</template>
