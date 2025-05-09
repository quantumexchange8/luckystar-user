<script setup>
import { useForm } from '@inertiajs/vue3';
import { Button, Dialog, InputText } from 'primevue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SelectChipGroup from '@/Components/SelectChipGroup.vue';
import { ref } from 'vue';

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
}

const form = useForm({
    payment_platform: '',
    payment_account_name: '',
    payment_platform_name: '',
    account_no: '',
    country: null,
    currency: '',
    bank_code: '',
});

const selectedPaymentAccountType = ref('crypto');
const paymentAccountTypes = ref([
    'bank',
    'crypto'
]);

const selectPaymentAccountType = (type) => {
    if (selectedPaymentAccountType !== type) {
        form.payment_account_name = '';
        form.account_no = '';
        form.bank_code = '';
    }
    selectedPaymentAccountType.value = type;
}

// crypto
const selectedCryptoNetwork = ref('trc20');
const cryptoNetworks = ref([
    'trc20',
    'bep20'
]);

const selectCryptoNetwork = (type) => {
    selectedCryptoNetwork.value = type;
}

const submit = () => {
    form.payment_platform = selectedPaymentAccountType.value;

    if (selectedPaymentAccountType.value === 'crypto') {
        form.payment_platform_name = selectedCryptoNetwork.value;
    }
    console.log(form)
    closeDialog();
    form.reset();
}

const closeDialog = () => {
    visible.value = false;
}
</script>

<template>
      <Button
        type="button"
        class="w-full min-w-32 md:w-auto"
        @click="openDialog"
        size="small"
    >
        {{ $t('public.add_account') }}
    </Button>

    <Dialog
        v-model:visible="visible"
        modal
        :header="$t('public.add_account')"
        class="dialog-xs md:dialog-md"
    >
        <form>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-5">
                 <!-- Account type-->
                <div class="flex flex-col items-start gap-1 self-stretch md:col-span-2">
                    <InputLabel for="payment_platform_name" :value="$t('public.payment_account_type')" />

                    <SelectChipGroup
                        v-model="selectedPaymentAccountType"
                        :items="paymentAccountTypes"
                        @update:modelValue="selectPaymentAccountType"
                    >
                        <template #option="{ item }">
                            {{ $t(`public.${item}`) }}
                        </template>
                    </SelectChipGroup>

                    <InputError :message="form.errors.payment_platform" />
                </div>

                 <!-- Crypto Account type-->
                <div
                    v-if="selectedPaymentAccountType === 'crypto'"
                    class="flex flex-col items-start gap-1 self-stretch md:col-span-2"
                >
                    <InputLabel for="crypto_network" :value="$t('public.crypto_network')" />

                    <SelectChipGroup
                        v-model="selectedCryptoNetwork"
                        :items="cryptoNetworks"
                        @update:modelValue="selectCryptoNetwork"
                    >
                        <template #option="{ item }">
                            {{ $t(`public.${item}`) }}
                        </template>
                    </SelectChipGroup>

                    <InputError :message="form.errors.payment_platform_name" />
                </div>

                 <!-- Bank Name -->
                <div
                    v-if="selectedPaymentAccountType === 'bank'"
                    class="flex flex-col gap-1 items-start self-stretch"
                >
                    <InputLabel
                        for="bank_name"
                        :value="$t('public.bank_name')"
                    />
                    <InputText
                        id="bank_name"
                        type="text"
                        class="block w-full"
                        v-model="form.payment_platform_name"
                        :placeholder="$t('public.bank_name')"
                    />
                    <InputError :message="form.errors.payment_platform_name" />
                </div>

                <!-- Bank Code -->
                <div
                    v-if="selectedPaymentAccountType === 'bank'"
                    class="flex flex-col gap-1 items-start self-stretch"
                >
                    <InputLabel
                        for="bank_code"
                        :value="$t('public.bank_code')"
                    />
                    <InputText
                        id="bank_code"
                        type="text"
                        class="block w-full"
                        v-model="form.bank_code"
                        :placeholder="$t('public.bank_code')"
                    />
                </div>

                <!-- Account name -->
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="payment_account_name"
                        :value="selectedPaymentAccountType === 'crypto'
                            ? $t('public.wallet_name')
                            : $t('public.account_name')"
                    />
                    <InputText
                        id="payment_account_name"
                        type="text"
                        class="block w-full"
                        v-model="form.payment_account_name"
                        :invalid="!!form.errors.payment_account_name"
                    />
                    <InputError :message="form.errors.payment_account_name" />
                </div>

                <!-- Account no -->
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="account_no"
                        :value="selectedPaymentAccountType === 'crypto'
                            ? $t('public.token_address')
                            : $t('public.account_no')"
                    />
                    <InputText
                        id="account_no"
                        type="text"
                        class="block w-full"
                        v-model="form.account_no"
                        :invalid="!!form.errors.account_no"
                    />
                    <InputError :message="form.errors.account_no" />
                </div>
            </div>

            <div class="pt-5 flex justify-end gap-3">
                <Button
                    class="w-auto px-8"
                    size="small"
                    severity="secondary"
                    @click="closeDialog"
                >
                    {{ $t('public.cancel') }}
                </Button>

                 <Button
                    class="w-auto px-8"
                    :disabled="form.processing"
                    @click="submit"
                    size="small"
                >
                    {{ $t('public.confirm') }}
                </Button>
            </div>
        </form>
    </Dialog>
</template>