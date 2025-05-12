<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SalesOverview from './SalesOverview.vue';
import SalesTable from './SalesTable.vue';


</script>

<template>
    <AuthenticatedLayout :title="$t('public.sales')">
        <div class="flex flex-col items-center gap-5">
            <SalesOverview />

            <SalesTable />
        </div>
    </AuthenticatedLayout>
</template>