<script setup>
import { IconBan } from '@tabler/icons-vue';
import { Button } from 'primevue';
</script>

<template>
    <Button
        type="button" 
        class="w-full"
        size="small"
        severity="danger"
        :label="$t('public.terminate')"
    >
        <template #icon>
            <IconBan size="20" stroke-width="1.5"/>
        </template>
    </Button>
</template>