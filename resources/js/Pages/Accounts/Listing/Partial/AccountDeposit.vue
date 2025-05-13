<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SelectChipGroup from '@/Components/SelectChipGroup.vue';
import { Button, Dialog, InputNumber, Select } from 'primevue';
import { ref, watch } from 'vue';
import { generalFormat } from '@/Composables/format';

const props = defineProps({
    account: Object,
});

console.log('account', props.account);

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
}

const closeDialog = () => {
    visible.value = false;
}

const form = useForm({
    trading_account_id: props.account.id,
    account_type_id: props.account.account_type_id,
    wallet_id: undefined,
    amount: null,
});

const {formatAmount} = generalFormat();

const selectedAccountType = ref();
const amount = ref(null);
const wallets = ref([]);
const selectedWallet = ref();

watch(amount, (newAmount) => {
    wallets.value = usePage().props.auth.wallets;

    if (newAmount === 0 || newAmount === null) {
        form.amount = amount.value;
        form.wallet_id = undefined;
    }
});

const submitForm = () => {
    if (selectedAccountType.value) {
        form.account_type_id = selectedAccountType.value;
    }

    if (amount.value > 0) {
        form.amount = amount.value;
        form.wallet_id = selectedWallet.value ? selectedWallet.value : null;
    }
    console.log(form);
}
</script>

<template>
    <Button
        type="button"
        severity="secondary"
        outlined
        class="w-full"
        size="small"
        @click="openDialog"
    >
        {{ $t('public.deposit') }}
    </Button>

    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-xs md:dialog-sm"
        :header="$t('public.deposit')"
    >
        <form @submit.prevent="submitForm">
            <div class="flex flex-col gap-5">
                <!-- <div
                    class="flex flex-col justify-center items-center px-8 py-4 gap-2 self-stretch bg-surface-100 dark:bg-surface-800">
                    <div class="text-surface-500 dark:text-surface-300 text-center text-xs font-medium">
                        #12345 - Current Account Balance
                    </div>
                    <div class="text-xl font-semibold">
                        <span>$ 1,234</span>
                    </div>
                </div> -->

                <!-- amount -->
                <div class="flex flex-col items-start gap-1 self-stretch">
                    <InputLabel
                        for="amount"
                        value="Amount"
                        :invalid="form.errors.amount"
                    />

                    <InputNumber
                        v-model="amount"
                        inputId="amount"
                        mode="currency"
                        currency="USD"
                        class="w-full"
                        locale="en-US"
                        placeholder="$0.00"
                        fluid
                        :invalid="!!form.errors.amount"
                    />

                    <span
                        class="text-xs font-normal text-surface-500"
                    >
                        {{ $t('public.minimum_amount') }} $30.00
                    </span>
                    <InputError :message="form.errors.amount" />
                </div>

                <!-- wallet selection -->
                <div v-if="amount > 0" class="flex flex-col items-start gap-1 self-stretch">
                    <InputLabel
                        for="wallet_id"
                        value="Select Wallet"
                        :invalid="form.errors.wallet_id"
                    />

                     <SelectChipGroup
                        v-model="selectedWallet"
                        :items="wallets"
                        value-key="id"
                    >
                        <template #option="{ item }">
                            <div class="flex flex-col text-center">
                                <div>
                                    {{ $t(`public.${item.type}`) }}
                                </div>
                                <div class="font-normal text-surface-500">
                                    {{ formatAmount(item.balance) }}
                                </div>
                            </div>
                        </template>
                    </SelectChipGroup>

                    <InputError :message="form.errors.wallet_id" />
                </div>
            </div>

            <div class="flex justify-end items-center pt-10 md:pt-7 gap-3 md:gap-4 self-stretch">
                <Button type="button" :label="$t('public.cancel')" severity="secondary" @click="closeDialog" class="flex flex-1 md:flex-none md:w-[120px]"></Button>
                <Button type="submit" :label="$t('public.confirm')" :disabled="form.processing" class="flex flex-1 md:flex-none md:w-[120px]"></Button>
            </div>
        </form>
    </Dialog>
</template>
