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

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
}

const closeDialog = () => {
    visible.value = false;
    amount.value = null;
    selectedWallet.value = null;
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

    form.post(route('accountDeposit'), {
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
        <form @submit.prevent="submitForm" class="flex flex-col gap-5 items-center self-stretch">
            <!-- amount -->
            <div class="flex flex-col items-start gap-1 self-stretch">
                <InputLabel
                    for="amount"
                    :invalid="!!form.errors.amount"
                >
                    {{ $t('public.deposit_amount') }}
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

                <span
                    class="text-xs font-normal text-surface-500"
                >
                    {{ $t('public.minimum_amount') }} {{ formatAmount(props.account.account_type.minimum_deposit, 2) }}
                </span>
                <InputError :message="form.errors.amount" />
            </div>

            <!-- wallet selection -->
            <div v-if="amount > 0" class="flex flex-col items-start gap-1 self-stretch">
                <InputLabel
                    for="wallet"
                    :invalid="!!form.errors.wallet_id"
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

                <InputError :message="form.errors.wallet_id" />
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
