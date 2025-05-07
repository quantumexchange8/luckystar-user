<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Button, Dialog, InputNumber, Select } from 'primevue';
import { ref } from 'vue';

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
}

const closeDialog = () => {
    visible.value = false;
}

const amount = ref(0);
const form = useForm({
    wallet_id: '',
    amount: '',
});
</script>

<template>
    <Button
        class="w-full dark:text-white text-surface bg-surface-0 dark:bg-surface-800 border-surface-300 dark:border-surface-700 enabled:hover:bg-white dark:hover:bg-surface-800 enabled:hover:border-surface-500 dark:hover:border-surface-600"
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
                <div
                    class="flex flex-col justify-center items-center px-8 py-4 gap-2 self-stretch bg-surface-100 dark:bg-surface-800">
                    <div class="text-surface-500 dark:text-surface-300 text-center text-xs font-medium">
                        #12345 - Current Account Balance
                    </div>
                    <div class="text-xl font-semibold">
                        <span>$ 1,234</span>
                    </div>
                </div>

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
                        prefix="$"
                        class="w-full"
                        inputClass="py-3 px-4"
                        :min="0"
                        :step="100"
                        showButtons
                        :minFractionDigits="2"
                        fluid
                        autofocus
                        :invalid="!!form.errors.amount"
                    />

                    <span
                        class="text-xs font-normal text-surface-500"
                    >
                        Minimum amount: $30.00
                    </span>
                    <InputError :message="form.errors.amount" />
                </div>

                <div class="flex flex-col items-start gap-1 self-stretch">
                    <InputLabel
                        for="wallet_id"
                        value="Select Wallet"
                        :invalid="form.errors.wallet_id"
                    />

                    <Select
                        class="w-full"
                        :invalid="!!form.errors.wallet_id"
                    >

                    </Select>

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