<script setup>
import { generalFormat } from '@/Composables/format';
import { useForm, usePage } from '@inertiajs/vue3';
import { Button, Dialog, InputNumber, Select } from 'primevue';
import SelectChipGroup from '@/Components/SelectChipGroup.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    wallet: Object,
});

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
    console.log(props.wallet)
}

const closeDialog = () => {
    visible.value = false;
}

const {formatAmount} = generalFormat();

const form = useForm({
    wallet_id: props.wallet.id,
    to_wallet_id: null,
    amount: null,    
});

const amount = ref(null);
const wallets = ref([]);
const selectedWallet = ref();

watch(amount, (newAmount) => {
    const allWallets = usePage().props.auth.wallets || [];

    // Filter out bonus wallets and the current wallet
    wallets.value = allWallets.filter(w => 
        w.type !== props.wallet.type && w.id !== props.wallet.id
    );

    if (newAmount === 0 || newAmount === null) {
        form.amount = amount.value;
        form.to_wallet_id = undefined;
        return;
    }

});

const submitForm = () => {
    if (amount.value > 0) {
        form.amount = amount.value;
        form.to_wallet_id = selectedWallet.value ? selectedWallet.value : null;
    }

    form.post(route('transaction.wallet.transfer'), {
        onSuccess: () => {
            closeDialog();
            form.reset();
            
            selectedWallet.value = null;
        }
    });

}
</script>

<template>
    <Button
        @click="openDialog"
        type="button"
        size="small"
        severity="contrast"
        class="w-full"
        :label="$t('public.transfer')"
    />

    <Dialog
        v-model:visible="visible"
        modal
        :header="$t('public.transfer')"
        class="dialog-xs md:dialog-sm"
    >
        <form @submit.prevent="submitForm" class="flex flex-col gap-5 self-stretch">
            <div
                class="flex flex-col justify-center items-center px-8 py-4 gap-2 self-stretch bg-surface-100 dark:bg-surface-800">
                <div class="text-surface-500 dark:text-surface-300 text-center text-xs font-medium">
                    {{ $t('public.current_balance') }}
                </div>
                <div class="text-xl font-semibold">
                    <span>{{ formatAmount(props.wallet.balance, 2) }}</span>
                </div>
            </div>

            <div class="flex flex-col items-start gap-1 self-stretch">
                <InputLabel
                    for="amount"
                    :invalid="!!form.errors.amount"
                >
                    {{ $t('public.transfer_amount') }}
                </InputLabel>

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

                <InputError :message="form.errors.amount" />
            </div>
         
            <!-- wallet selection -->
            <div v-if="amount > 0" class="flex flex-col items-start gap-1 self-stretch">
                <InputLabel
                    for="wallet"
                    :invalid="!!form.errors.to_wallet_id"
                >
                    {{ $t('public.select_wallet') }}
                </InputLabel>

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

                <InputError :message="form.errors.to_wallet_id" />
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 justify-end self-stretch pt-2 w-full">
                <Button
                    type="button"
                    :label="$t('public.cancel')"
                    severity="secondary"
                    @click="closeDialog"
                    class="w-full md:w-fit"
                />
                <Button
                    type="submit"
                    :label="$t('public.confirm')"
                    :disabled="form.processing"
                    class="w-full md:w-fit"
                    @click.prevent="submitForm"
                />
            </div>
        </form>
    </Dialog>
</template>