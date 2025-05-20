<script setup>
import {Button, Dialog, Select, InputNumber, Avatar} from 'primevue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import {ref, watch} from 'vue';
import { useForm } from '@inertiajs/vue3';
import {generalFormat} from "@/Composables/format.js";
import TermsAndCondition from "@/Components/TermsAndCondition.vue";

const props = defineProps({
    strategy: Object,
})

const visible = ref(false);
const {formatAmount, formatNameLabel} = generalFormat();

const openDialog = () => {
    visible.value = true;
    getInvestorAccounts();
}

const accounts = ref([]);
const selectedAccount = ref();
const loadingAccounts = ref(false);

const getInvestorAccounts = async () => {
    loadingAccounts.value = true;
    try {
        const response = await axios.get(`/getInvestorAccounts?type=${props.strategy.account_type_id}&leverage=${props.strategy.leverage}`);
        accounts.value = response.data.accounts;
    } catch (error) {
        console.error('Error fetching accounts:', error);
    } finally {
        loadingAccounts.value = false;
    }
};


const form = useForm({
    trading_master_id: props.strategy.id,
    amount: null,
    account_id: '',
})

watch(selectedAccount, (acc) => {
    form.account_id = acc.id;
    form.amount = Number(acc.balance);
});

const submitForm = () => {
    form.post(route('strategy.investStrategy'), {
        onSuccess: () => {
            closeDialog();
            form.reset();
        },
    })
}

const closeDialog = () => {
    visible.value = false;
}

</script>

<template>
    <Button
        type="button"
        size="small"
        class="w-full"
        :label="$t('public.invest_now')"
        @click="openDialog"
    />

    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-xs md:dialog-md"
        :header="$t('public.invest_now')"
    >
        <form>
            <div class="flex flex-col gap-8">
                <div class="flex flex-col justify-center items-center gap-4 self-stretch py-5 px-6 bg-surface-50 dark:bg-surface-800">
                    <div class="w-full flex items-center gap-4 self-stretch">
                        <Avatar
                            v-if="strategy?.media"
                            :image="strategy.group_image"
                            shape="circle"
                            class="min-w-10"
                        />
                        <Avatar
                            v-else
                            :label="formatNameLabel(strategy.master_name)"
                            shape="circle"
                            class="min-w-10"
                        />
                        <div class="flex flex-col items-start">
                            <div class="self-stretch truncate font-bold">
                                {{ strategy.master_name }}
                            </div>
                            <div class="self-stretch truncate text-surface-500 text-sm">
                                {{ strategy.trader_name }}
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center items-center self-stretch border-t border-solid border-surface-200 dark:border-surface-700">
                    </div>

                    <div class="flex flex-col self-stretch gap-3">
                        <div class="flex flex-col items-start font-bold text-sm">
                            {{ $t('public.strategy_information') }}
                        </div>

                        <div class="flex flex-col items-center self-stretch gap-1">
                            <div class="flex items-center self-stretch gap-3 py-1">
                                <span class="w-full text-surface-500 text-xs">{{ $t('public.account_no') }}</span>
                                <span class="font-medium text-surface-950 dark:text-white w-full text-sm">{{ strategy.meta_login }}</span>
                            </div>

                            <div class="flex items-center self-stretch gap-3 py-1">
                                <span class="w-full text-surface-500 text-xs">{{ $t('public.minimum_investment') }}</span>
                                <span class="font-medium text-surface-950 dark:text-white w-full text-sm">{{ formatAmount(strategy.minimum_investment) }}</span>
                            </div>

                            <div class="flex items-center self-stretch gap-3 py-1">
                                <span class="w-full text-surface-500 text-xs">{{ $t('public.minimum_investment_period') }}</span>
                                <div v-if="strategy.investment_period !== 0" class="font-medium text-surface-950 dark:text-white w-full text-sm">
                                    {{ strategy.investment_period }}
                                    {{ $t(`public.${strategy.investment_period_type}`) }}
                                </div>
                                <div v-else class="font-medium w-full text-sm">
                                    {{ $t('public.lock_free') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 items-start self-stretch">
                    <div class="flex flex-col items-start font-bold text-sm">
                        {{ $t('public.fees_and_conditions') }}
                    </div>
                    <div class="flex flex-col items-center gap-1 self-stretch">
                        <!-- Table Header -->
                        <div
                            class="flex justify-between items-center py-2 self-stretch border-b border-surface-200 bg-surface-100 dark:border-surface-700 dark:bg-surface-800">
                            <div class="flex items-center px-2 w-full text-surface-950 dark:text-white text-xs font-semibold uppercase">
                                {{ $t('public.days') }}
                            </div>
                            <div class="flex items-center px-2 w-full text-surface-950 dark:text-white text-xs font-semibold uppercase">
                                {{ $t('public.fee_percentage') }} (%)
                            </div>
                        </div>

                        <!-- Rows -->
                        <div class="flex flex-col items-center self-stretch max-h-[220px] overflow-y-auto">
                            <div
                                v-for="fee in strategy.management_fees"
                                :key="fee.id"
                                class="flex justify-between gap-3 my-1 items-center self-stretch"
                            >
                                <!-- Days -->
                                <div class="w-full text-sm pl-2">
                                    {{ fee.management_days }}
                                </div>
                                <!-- Percentage -->
                                <div class="w-full text-sm">
                                    {{ formatAmount(fee.management_percentage, 0, '') }}%
                                </div>
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
                                :invalid="!!form.errors.account_id"
                            />
                            <Select
                                v-model="selectedAccount"
                                :options="accounts"
                                class="w-full"
                                :placeholder="$t('public.select_account')"
                                :loading="loadingAccounts"
                                :invalid="!!form.errors.account_id"
                                :disabled="!accounts.length"
                            >
                                <template #value="{ value, placeholder }">
                                    <div v-if="!accounts.length" class="text-surface-400 dark:text-surface-700">
                                        {{ $t('public.no_account_found') }}
                                    </div>
                                    <div v-else-if="value" class="flex items-center gap-3">
                                        {{ value.meta_login }}
                                    </div>
                                    <div v-else class="text-surface-400 dark:text-surface-700">
                                        {{ placeholder }}
                                    </div>
                                </template>

                                <template #option="{ option }">
                                    {{ option.meta_login }}
                                </template>
                            </Select>
                            <InputError :message="form.errors.account_id" />
                        </div>

                        <div class="flex flex-col items-start gap-1 self-stretch">
                            <InputLabel
                                for="amount"
                                :value="$t('public.investment_amount')"
                                :invalid="!!form.errors.amount"
                            />
                            <InputNumber
                                v-model="form.amount"
                                inputId="amount"
                                class="w-full"
                                mode="currency"
                                currency="USD"
                                locale="en-US"
                                placeholder="$0.00"
                                :min="0"
                                :max="Number(selectedAccount?.balance) ?? 0"
                                :step="100"
                                showButtons
                                fluid
                                :invalid="!!form.errors.amount"
                                :disabled="!accounts.length"
                            />
                            <InputError :message="form.errors.amount" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 text-xs">
                {{ $t('public.accept_term_1') }}
<!--                <TermsAndCondition-->
<!--                    :termsLabel="$t('public.accept_term_2')"-->
<!--                />-->
                {{ $t('public.accept_term_2') }}
            </div>

            <div class="flex justify-end items-center pt-10 md:pt-7 gap-3 md:gap-4 self-stretch">
                <Button
                    type="button"
                    :label="$t('public.cancel')"
                    severity="secondary"
                    @click="closeDialog"
                    class="w-full md:w-fit"
                />
                <Button
                    type="submit"
                    :label="$t('public.invest_now')"
                    :disabled="form.processing || !accounts.length"
                    class="w-full md:w-fit"
                    @click="submitForm"
                />
            </div>
        </form>
    </Dialog>
</template>
