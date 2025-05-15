<script setup>
import { generalFormat } from '@/Composables/format';
import { Button, Dialog, InputNumber, Skeleton } from 'primevue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SelectChipGroup from '@/Components/SelectChipGroup.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    wallet: Object,
});

console.log('wallet',props.wallet)

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
    getTopUpProfiles();
}

const {formatAmount} = generalFormat();

const loadingOptions = ref(false);
const topUpOptions = ref([]);

const getTopUpProfiles = async () => {
    loadingOptions.value = true;
    try {
        const response = await axios.get('/get_top_up_profile');
        topUpOptions.value = response.data.topUpProfiles;

        console.log('topup',response)

    } catch(error) {
        console.error(error);
    } finally {
        loadingOptions.value = false;
    }
}

const amount = ref(null);
const selectedTopUpType = ref();

const form = useForm({
    wallet_id: props.wallet.id,
    topUpProfile_id: '',
    amount: '',
});

const submitForm = () => {

    if(selectedTopUpType.value) {
        form.topUpProfile_id = selectedTopUpType.value;
    }

    if(amount.value > 0) {
        form.amount = amount.value;
    }

    form.post(route('transaction.wallet.topUp'), {
        onSuccess: () => {
            closeDialog();
            form.reset();

            selectedTopUpType.value = null;
        }
    });

    console.log('form', form)
}

const closeDialog = () => {
    visible.value = false;
}
</script>

<template>
    <Button
        @click="openDialog"
        type="button"
        size="small"
        severity="contrast"
        class="w-full"
        :label="$t('public.top_up')"
    />

    <Dialog
        v-model:visible="visible"
        modal
        :header="$t('public.top_up')"
        class="dialog-xs md:dialog-md"
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

            <div v-if="topUpOptions && loadingOptions === false" class="flex flex-col gap-5">
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="topUpProfile_id"
                        :value="$t('public.select_top_up_profile')"
                        :invalid="!!form.errors.topUpProfile_id"
                    />
                    <SelectChipGroup
                        v-model="selectedTopUpType"
                        :items="topUpOptions"
                        value-key="id"
                    >
                        <template #option="{ item }">
                            {{ item.payment_name }}
                        </template>
                    </SelectChipGroup>
                    <InputError :message="form.errors.topUpProfile_id" />
                </div>

                <div class="flex flex-col gap-1 items-start self-stretch">
                    <InputLabel
                        for="amount"
                        :value="$t('public.amount')"
                        :invalid="!!form.errors.amount"
                    />
                    <InputNumber 
                        v-model="amount"
                        inputId="amount"
                        mode="currency"
                        currency="USD"
                        locale="en-US"
                        placeholder="$0.00"
                        fluid
                        :invalid="!!form.errors.amount"
                    />
                    <InputError :message="form.errors.amount" />
                </div>
            </div>

            <div v-else class="flex flex-col gap-5">
                <div class="flex flex-col gap-1 items-start self-stretch">
                    <Skeleton width="8rem" class="my-1"></Skeleton>
                    <div class="flex flex-row gap-4 self-stretch">
                        <Skeleton width="16rem" height="3rem" class="my-1"></Skeleton>
                        <Skeleton width="16rem" height="3rem" class="my-1"></Skeleton>
                    </div>
                </div>

                <div class="flex flex-col gap-1 items-start self-stretch">
                    <Skeleton width="8rem" class="my-1"></Skeleton>
                    <Skeleton width="33rem" height="3rem" class="my-1"></Skeleton>
                </div>
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