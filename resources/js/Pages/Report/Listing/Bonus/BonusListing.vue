<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BonusOverview from './BonusOverview.vue';
import BonusTable from './BonusTable.vue';



</script>

<template>
    <AuthenticatedLayout :title="$t('public.trade_history')">
        <div class="flex flex-col items-center gap-5">
            <BonusOverview />

            <BonusTable />
        </div>
    </AuthenticatedLayout>
</template>
