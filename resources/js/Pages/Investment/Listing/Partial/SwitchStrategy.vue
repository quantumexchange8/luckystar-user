<script setup>
import { IconArrowsExchange } from '@tabler/icons-vue';
import { Button } from 'primevue';
</script>

<template>
    <Button
        severity="secondary"
        type="button"
        rounded
        text
    >
        <template #icon>
            <IconArrowsExchange size="20" stroke-width="1.5"/>
        </template>
    </Button>
</template>