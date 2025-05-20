<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StrategyView from './StrategyView.vue';

defineProps({
    strategiesCount: Number,
})
</script>

<template>
    <AuthenticatedLayout :title="$t('public.strategy')">
        <div class="flex flex-col gap-5 items-center self-stretch w-full">
            <StrategyView
                :strategiesCount="strategiesCount"
            />
        </div>
    </AuthenticatedLayout>
</template>
