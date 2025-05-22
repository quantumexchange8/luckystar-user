<script setup>
import {  IconSettings } from '@tabler/icons-vue';
import { Button, Dialog } from 'primevue';
import { ref } from 'vue';

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
}

const closeDialog = () => {
    visible.value = false;
}
</script>

<template>
     <Button
        severity="secondary"
        rounded
        class="shrink-0 dark:text-white text-surface bg-surface-0 dark:bg-surface-800 border-surface-300 dark:border-surface-700 enabled:hover:bg-white dark:hover:bg-surface-800 enabled:hover:border-surface-500 dark:hover:border-surface-600"
        @click="openDialog"
    >
        <template #icon>
            <IconSettings size="16" stroke-width="1.5" />
        </template>
    </Button>

    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-xs md:dialog-md"
        :header="$t('public.subscription_detail')"
    >

    </Dialog>
</template>