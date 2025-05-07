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
                {{ $t('public.work_information') }}
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
                        {{ $t('public.employment_status') }}
                    </div>
                    <div>
                        Self Employed
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.occupation') }}
                    </div>
                    <div>
                        Software Consultant
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.industry') }}
                    </div>
                    <div>
                        Finance
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.source_of_income') }}
                    </div>
                    <div>
                        Salary
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.annual_income') }}
                    </div>
                    <div>
                        $100,000+
                    </div>
                </div>
                <div class="grid grid-cols-2 w-full">
                    <div class="text-surface-500">
                        {{ $t('public.net_worth') }}
                    </div>
                    <div>
                        $200,000+
                    </div>
                </div>
            </div>
        </template>
    </Card>
</template>
