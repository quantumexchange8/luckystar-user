<script setup>
import { Button } from "primevue";
import TopUp from "./TopUp.vue";
import Withdrawal from "./withdrawal.vue";
import WalletTransfer from "./WalletTransfer.vue";

const props = defineProps({
    wallet: Object,
})

</script>

<template>
    <template v-if="props.wallet.type === 'cash_wallet'">
        <TopUp 
            :wallet="props.wallet"
        />
        <Withdrawal />
    </template>

    <template v-if="props.wallet.type === 'bonus_wallet'">
        <WalletTransfer
            :wallet="props.wallet"
        />
        <Withdrawal />
    </template>
</template>