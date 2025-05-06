<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import SelectChipGroup from "@/Components/SelectChipGroup.vue";
import InputError from "@/Components/InputError.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import { ref, watch } from "vue";
import {
    Button,
    Select,
    InputNumber,
} from "primevue";
import {generalFormat} from "@/Composables/format.js";

const props = defineProps({
    accountTypes: Array,
});

const emit = defineEmits(['update:visible']);
const {formatAmount} = generalFormat();

const selectedAccountType = ref();

const form = useForm({
    account_type_id: '',
    leverage: null,
    amount: null,
    wallet_id: undefined
});

watch(selectedAccountType, () => {
    getLeverages();
});

const leverages = ref([]);
const loadingLeverages = ref(false);
const selectedLeverage = ref();

const getLeverages = async () => {
    loadingLeverages.value = true;
    try {
        const response = await axios.get(route('getLeverages', selectedAccountType.value));
        leverages.value = response.data.leverages;
    } catch (error) {
        console.error('Error fetching leverages:', error);
    } finally {
        loadingLeverages.value = false;
    }
}

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
        form.leverage = selectedLeverage.value;
    }

    if (amount.value > 0) {
        form.amount = amount.value;
        form.wallet_id = selectedWallet.value ? selectedWallet.value : null;
    }

    form.post(route('createAccount'), {
        onSuccess: () => {
            closeDialog();
            form.reset();

            selectedAccountType.value = null;
            selectedLeverage.value = null;
            selectedWallet.value = null;
        },
    })
}

const closeDialog = () => {
    emit('update:visible', false);
}
</script>

<template>
    <form class="flex flex-col gap-5 items-center self-stretch">
        <!-- Account Type -->
        <div class="flex flex-col gap-1 items-start self-stretch">
            <InputLabel
                for="account_type_id"
                :value="$t('public.select_account_type')"
                :invalid="!!form.errors.account_type_id"
            />
            <SelectChipGroup
                v-model="selectedAccountType"
                :items="accountTypes"
                value-key="id"
            >
                <template #option="{ item }">
                    {{ item.name }}
                </template>
            </SelectChipGroup>
            <InputError :message="form.errors.account_type_id" />
        </div>

        <!-- Leverage -->
        <div class="flex flex-col gap-1 items-start self-stretch">
            <InputLabel
                for="leverage"
                :value="$t('public.leverage')"
                :invalid="!!form.errors.leverage"
            />
            <Select
                v-model="selectedLeverage"
                :options="leverages"
                class="w-full"
                :placeholder="$t('public.select_leverage')"
                :disabled="!selectedAccountType"
                :loading="loadingLeverages"
                :invalid="!!form.errors.leverage"
            >
                <template #value="{ value, placeholder }">
                    <div v-if="value" class="flex items-center gap-3">
                        {{ value.setting_leverage.label }}
                    </div>
                    <div v-else>
                        {{ placeholder }}
                    </div>
                </template>

                <template #option="{ option }">
                    {{ option.setting_leverage.label }}
                </template>
            </Select>
            <InputError :message="form.errors.leverage" />
        </div>

        <!-- Amount -->
        <div class="flex flex-col gap-1 items-start self-stretch">
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
                locale="en-US"
                placeholder="$0.00"
                fluid
            />
            <InputError :message="form.errors.amount" />
        </div>

        <!-- Wallets -->
        <div
            v-if="amount > 0"
            class="flex flex-col gap-1 items-start self-stretch"
        >
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
</template>
