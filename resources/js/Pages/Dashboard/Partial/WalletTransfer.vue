<script setup>
import { generalFormat } from '@/Composables/format';
import { Button, Dialog } from 'primevue';
import { ref } from 'vue';

const props = defineProps({
    wallet: Object,
});

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
}

const {formatAmount} = generalFormat();
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
        :header="$t('public.top_up')"
        class="dialog-xs md:dialog-md"
    >
        <form>
            <div class="flex flex-col gap-5">
                <div
                    class="flex flex-col justify-center items-center px-8 py-4 gap-2 self-stretch bg-surface-100 dark:bg-surface-800">
                    <div class="text-surface-500 dark:text-surface-300 text-center text-xs font-medium">
                        {{ $t('public.current_balance') }}
                    </div>
                    <div class="text-xl font-semibold">
                        <span>{{ formatAmount(props.wallet.balance, 2) }}</span>
                    </div>
                </div>
            </div>
        </form>
    </Dialog>
</template>