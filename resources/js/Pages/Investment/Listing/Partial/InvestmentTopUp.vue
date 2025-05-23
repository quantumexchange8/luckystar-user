<script setup>
import { IconWallet } from '@tabler/icons-vue';
import { Button, Dialog } from 'primevue';
import { ref } from 'vue';

const visible = ref(false);

const openDialog = () => {
    visible.value = true;
}

const closeDialog = () => {
    visible.value = true;
}
</script>

<template>
    <Button
        type="button" 
        class="w-full"
        size="small"
        :label="$t('public.top_up')"
        @click="openDialog"
    >   
        <template #icon>
            <IconWallet size="20" stroke-width="1.5"/>
        </template>
    </Button>

    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-xs md:dialog-sm"
        :header="$t('public.top_up')"  
    >
        
    </Dialog>
</template>