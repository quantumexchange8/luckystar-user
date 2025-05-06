<script setup>
import { IconAlertCircleFilled } from '@tabler/icons-vue';
import { Button, Message, Card } from 'primevue';
import AddAccount from './Partial/AddAccount.vue';

defineProps({
    accountTypes: Array,
})
</script>

<template>
    <Message severity="error" closable class="w-full">
        <template #icon>
            <IconAlertCircleFilled size="30" stroke-width="1.5" />
        </template>
        <div class="font-semibold text-base mb-1">Incomplete Profile Notice</div>
        <div class="text-sm">Complete your profile details before creating an account.</div>
    </Message>

    <Card class="w-full">
        <template #content>
            <div class="font-bold text-lg mb-2">Open an Account Today!</div> <!-- Added margin-bottom for gap -->
            <div class="flex flex-col gap-8">
                <p class="m-0 text-sm text-surface-700 dark:text-surface-200">
                    Create your account to join powerful trading strategies managed by top-performing investors —
                    effortlessly grow your portfolio with expert guidance.
                </p>

                <div class="flex flex-col gap-3 sm:flex-row sm:gap-5">
                    <AddAccount
                        :accountTypes="accountTypes"
                    />
                </div>
            </div>
        </template>
    </Card>
</template>
