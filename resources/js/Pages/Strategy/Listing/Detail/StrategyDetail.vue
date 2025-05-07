<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Card, Button, Breadcrumb } from 'primevue';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import StrategyInfo from './StrategyInfo.vue';
import StrategyFolio from './StrategyFolio.vue';

const home = ref({
    label: 'strategy',
    route: route('strategy')
});

const items = ref([
    { label: 'Strategy Name' },
]);
</script>

<template>
    <AuthenticatedLayout :title="$t('public.strategy_detail')">
        <div class="flex flex-col gap-5 items-center self-stretch">
            <div class="flex items-center self-stretch">
                <Breadcrumb :home="home" :model="items">
                    <template #item="{ item }">
                        <Link v-if="item.route" :href="item.route">
                            <span :class="[item.icon, 'text-color']" />
                            <span class="text-primary font-semibold hover:text-primary-600">{{ $t(`public.${item.label}`) }}</span>
                        </Link>
                        <span v-else class="text-surface-700 dark:text-surface-0">{{ item.label }} - {{ $t('public.details') }}</span>
                    </template>
                </Breadcrumb>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-5 self-stretch">
                <StrategyInfo />
            </div>

            <div class="flex items-center self-stretch">
                <StrategyFolio />
            </div>
        </div>

    </AuthenticatedLayout>
</template>