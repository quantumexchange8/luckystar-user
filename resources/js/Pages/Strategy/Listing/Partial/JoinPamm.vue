<script setup>
import { Button, Dialog, Select, InputNumber } from 'primevue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
}

const closeDialog = () => {
    visible.value = false;
}

const amount = ref(0);
const form = useForm({
    amount: '',
    account: '',
})
</script>

<template>
    <Button class="w-full" @click="openDialog">
        {{ $t('public.join_pamm') }}
    </Button>

    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-xs md:dialog-md"
        :header="$t('public.join_pamm')"
    >
        <form>
            <div class="flex flex-col gap-8">
                <div class="flex flex-col justify-center items-center gap-4 self-stretch py-5 px-6 bg-surface-50 dark:bg-surface-800">
                    <div class="w-full flex items-center gap-4 self-stretch">
                        <img
                            class="object-cover w-11 h-11 rounded-full"
                            src="https://img.freepik.com/free-icon/user_318-159711.jpg"
                            alt="masterPic"
                        />
                        <div class="flex flex-col items-start">
                            <div class="self-stretch truncate text-surface-950 dark:text-white font-bold">
                                Strategy Name
                            </div>
                            <div class="self-stretch truncate text-surface-500 text-sm">
                                Username
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center items-center self-stretch border-t border-solid border-surface-200 dark:border-surface-700">
                    </div>

                    <div class="flex flex-col self-stretch gap-3">
                        <div class="flex flex-col items-start font-bold text-sm">
                            {{ $t('public.fees_and_conditions') }}
                        </div>

                        <div class="flex flex-col items-center self-stretch gap-1">
                            <div class="flex items-center self-stretch gap-3 py-1">
                                <span class="w-full text-surface-500 text-xs">{{ $t('public.minimum_investment') }}</span>
                                <span class="font-medium w-full text-surface-950 dark:text-white text-sm">$123</span>
                            </div>

                            <div class="flex items-center self-stretch gap-3 py-1">
                                <span class="w-full text-surface-500 text-xs">{{ $t('public.minimum_investment_period') }}</span>
                                <span class="font-medium w-full text-surface-950 dark:text-white text-sm">12 months</span>
                            </div>

                            <div class="flex items-center self-stretch gap-3 py-1">
                                <span class="w-full text-surface-500 text-xs">{{ $t('public.performance_fee') }}</span>
                                <span class="font-medium w-full text-surface-950 dark:text-white text-sm">10%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-5 self-stretch">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 w-full">
                        
                        <div class="flex flex-col items-start gap-1 self-stretch">
                            <InputLabel
                                for="amount"
                                :value="$t('public.managed_account')"
                                :invalid="form.errors.account"
                            />
    
                            <Select
                                class="w-full"
                                :invalid="!!form.errors.account"
                            >
    
                            </Select>
    
                            <InputError :message="form.errors.account" />
                        </div>
    
                        <div class="flex flex-col items-start gap-1 self-stretch">
                            <InputLabel
                                for="amount"
                                :value="$t('public.investment_amount')"
                                :invalid="form.errors.amount"
                            />
    
                            <InputNumber 
                                v-model="amount"
                                inputId="amount"
                                class="w-full"
                                prefix="$"
                                inputClass="py-3 px-4"
                                :min="0"
                                :step="100"
                                showButtons
                                :minFractionDigits="2"
                                fluid
                                :invalid="!!form.errors.amount"
                            />
    
                            <InputError :message="form.errors.amount" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end items-center pt-10 md:pt-7 gap-3 md:gap-4 self-stretch">
                <Button type="button" :label="$t('public.cancel')" severity="secondary" @click="closeDialog" class="flex flex-1 md:flex-none md:w-[120px]"></Button>
                <Button type="submit" :label="$t('public.confirm')" :disabled="form.processing" class="flex flex-1 md:flex-none md:w-[120px]"></Button>
            </div>
        </form>
    </Dialog>
</template>