<script setup>
import {
    Button,
    Dialog
} from 'primevue';
import {ref} from "vue";
import CreateAccountForm from "@/Pages/Accounts/Listing/Partial/CreateAccountForm.vue";

defineProps({
    accountTypes: Array,
})

const visible = ref(false);
const dialogType = ref('');

const openDialog = (type) => {
    visible.value = true;
    dialogType.value = type;
}

</script>

<template>
    <Button
        type="button"
        :label="$t('public.open_trade_account')"
        @click="openDialog('open_trade_account')"
    />

    <Button
        type="button"
        outlined
        :label="$t('public.demo_account')"
        @click="openDialog('demo_account')"
    />

    <Dialog
        v-model:visible="visible"
        modal
        :header="$t(`public.${dialogType}`)"
        class="dialog-xs md:dialog-sm"
    >
        <template v-if="dialogType === 'open_trade_account'">
            <CreateAccountForm
                :accountTypes="accountTypes"
                @update:visible="visible = false"
            />
        </template>
    </Dialog>
</template>
