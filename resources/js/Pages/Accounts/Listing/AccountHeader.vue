<script setup>
import { IconAlertCircleFilled } from '@tabler/icons-vue';
import { Button, Message, Card } from 'primevue';
</script>

<template>
    <Message severity="error" closable class="w-full">
        <template #icon>
            <IconAlertCircleFilled size="30" stroke-width="1.5" />
        </template>
        <div class="font-semibold text-base mb-1">Incomplete Profile Notice</div>
        <div class="text-sm">Complete your profile details before creating an account.</div>
    </Message>

    <Card class="w-full px-6 py-5">
        <template #content>
            <div class="font-bold text-lg mb-2">Open an Account Today!</div> <!-- Added margin-bottom for gap -->
            <div class="flex flex-col gap-8">
                <p class="m-0 text-sm text-surface-700 dark:text-surface-200">
                    Create your account to join powerful trading strategies managed by top-performing investors —
                    effortlessly grow your portfolio with expert guidance.
                </p>

                <div class="flex flex-wrap gap-3 sm:gap-5">
                    <Button class="px-6 w-[200px] sm:w-auto">
                        <span>Open Live Account</span>
                    </Button>

                    <Button outlined class="px-6 w-[200px] sm:w-auto">
                        <span>Demo Account</span>
                    </Button>
                </div>
            </div>
        </template>
    </Card>
</template>