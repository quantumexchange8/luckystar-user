<script setup>
import {Card, Button} from "primevue";
import {IconEdit, IconAlertSquareRoundedFilled} from "@tabler/icons-vue";

const props = defineProps({
    user: Object,
})
</script>

<template>
    <Card class="w-full">
        <template #title>
            <div class="flex gap-5 items-center justify-between">
                {{ $t('public.general_information') }}
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
                        {{ $t('public.ic_passport_no') }}
                    </div>
                    <div>
                        {{ user?.identity_number }}
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.gender') }}
                    </div>
                    <div v-if="user?.gender">
                        {{ user.gender }}
                    </div>
                    <div v-else class="flex items-center text-red-500 gap-1 text-xs">
                        <IconAlertSquareRoundedFilled size="12" stroke-width="1.5" />
                        {{ $t('public.missing_field') }}
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.date_of_birth') }}
                    </div>
                    <div v-if="user?.dob">
                        {{ user.dob }}
                    </div>
                    <div v-else class="flex items-center text-red-500 gap-1 text-xs">
                        <IconAlertSquareRoundedFilled size="12" stroke-width="1.5" />
                        {{ $t('public.missing_field') }}
                    </div>
                </div>
            </div>
        </template>
    </Card>
</template>
