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
                {{ $t('public.beneficiary_information') }}
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
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.full_name') }}
                    </div>
                    <div>
                        Lucky Star
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.relationship') }}
                    </div>
                    <div>
                        Boss
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.ic_passport_no') }}
                    </div>
                    <div>
                        709854123124
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.phone_number') }}
                    </div>
                    <div>
                        60 121412412
                    </div>
                </div>
            </div>
        </template>
    </Card>
</template>
