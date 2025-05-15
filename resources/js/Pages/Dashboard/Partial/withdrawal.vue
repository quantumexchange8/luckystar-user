<script setup>
import { Button } from 'primevue';
</script>

<template>
    <Button
        type="button"
        severity="secondary"
        size="small"
        class="w-full"
        :label="$t('public.withdrawal')"
    />
</template>