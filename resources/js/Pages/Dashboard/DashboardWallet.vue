<script setup>
import {
    Button,
} from "primevue";
import {IconWallet} from "@tabler/icons-vue";
import {generalFormat} from "@/Composables/format.js";
import WalletAction from "./Partial/WalletAction.vue";

defineProps({
    wallets: Object,
});

const {formatAmount} = generalFormat();
</script>

<template>
    <div
        v-for="wallet in wallets"
        :class="[
            'py-3 px-5 w-full rounded-2xl self-stretch flex flex-col items-start gap-2 shadow-toast transition-colors duration-200',
            {
                'bg-blue-400 dark:bg-blue-900': wallet.type === 'cash_wallet',
                'bg-violet-400 dark:bg-violet-900': wallet.type === 'bonus_wallet',
            }
        ]"
    >
        <div class="flex items-center justify-between gap-3 self-stretch relative">
            <div class="flex flex-col">
                <div class="text-sm md:text-base font-medium text-white">
                    {{ $t(`public.${wallet.type}`) }}
                </div>
                <div
                    :class="[
                        'text-xs ',
                        {
                            'text-blue-100': wallet.type === 'cash_wallet',
                            'text-violet-100': wallet.type === 'bonus_wallet',
                        }
                    ]"
                >
                    {{ wallet.address }}
                </div>
            </div>

            <div
                :class="[
                    'flex items-center justify-center w-14 h-14 rounded-full grow-0 shrink-0 absolute top-0 right-0',
                    {
                        'bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-300 border-[6px] border-blue-400/40': wallet.type === 'cash_wallet',
                        'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300 border-[6px] border-violet-400/40': wallet.type === 'bonus_wallet',
                    }
                ]"
            >
              <IconWallet size="32" stroke-width="1.5 "/>
            </div>
        </div>
        <div class="text-2xl font-medium text-white">
            {{ formatAmount(wallet.balance) }}
        </div>
        <div class="flex gap-3 items-center self-stretch">
            <WalletAction 
                :wallet="wallet"
            />
        </div>
    </div>
</template>
