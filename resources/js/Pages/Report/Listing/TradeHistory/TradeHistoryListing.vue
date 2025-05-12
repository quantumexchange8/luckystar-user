<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TradeHistoryOverview from './TradeHistoryOverview.vue';
import TradeHistoryTable from './TradeHistoryTable.vue';


</script>

<template>
    <AuthenticatedLayout :title="$t('public.trade_history')">
        <div class="flex flex-col items-center gap-5">
            <TradeHistoryOverview />

            <TradeHistoryTable />
        </div>
    </AuthenticatedLayout>
</template>
