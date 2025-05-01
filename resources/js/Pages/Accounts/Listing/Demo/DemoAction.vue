<script setup>
import { IconTrash } from '@tabler/icons-vue';
import { Button, useConfirm } from 'primevue';

const confirm = useConfirm();

const requireConfirmation = (action_type) => {
    const messages = {
        delete_item: {
            group: 'headless-error',
            header: 'public.delete_demo_account',
            text: 'public.delete_account_demo_caption',
            cancelButton: 'public.cancel',
            acceptButton: 'Yes, delete it',
           
        },
    };

    const { group, header, text, dynamicText, suffix, actionType, cancelButton, acceptButton, action } = messages[action_type];

    confirm.require({
        group,
        header,
        actionType,
        text,
        dynamicText,
        suffix,
        cancelButton,
        acceptButton,
        accept: action
    });
};

const handleDelete = () => {
    requireConfirmation('delete_item')
}
</script>

<template>
     <Button
        severity="secondary"
        size="small"
        type="button"
        rounded
        text
        class="!p-2"
        @click="handleDelete()"
        aria-haspopup="true"
        aria-controls="overlay_tmenu"
    >
        <IconTrash size="20" stroke-width="1.5"/>
    </Button>
</template>