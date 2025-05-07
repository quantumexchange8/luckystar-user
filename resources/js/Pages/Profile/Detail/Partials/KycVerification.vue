<script setup>
import {Button, Card} from "primevue";
import {IconAlertSquareRoundedFilled, IconEdit} from "@tabler/icons-vue";

const props = defineProps({
    user: Object,
})
</script>

<template>
    <Card class="w-full">
        <template #title>
            <div class="flex gap-5 items-center justify-between">
                {{ $t('public.kyc_verification') }}
                <Button
                    type="button"
                    icon="IconEdit"
                    severity="secondary"
                    rounded
                    text
                >
                    <template #icon>
                        <IconEdit size="16" stroke-width="1.5" />
                    </template>
                </Button>
            </div>
        </template>

        <template #content>
            <div class="flex flex-col gap-3 items-center">

            </div>
        </template>
    </Card>
</template>
