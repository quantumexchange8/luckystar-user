<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Tab, TabList, TabPanels, Tabs, TabPanel } from 'primevue';
import Deposit from './Deposit.vue';
import Withdrawal from './Withdrawal.vue';
import { onMounted, ref, h } from 'vue';
import TransactionOverview from './TransactionOverview.vue';

const tabs = ref([
    {
        title: 'deposit',
        component: h(Deposit),
        value: '0'
    },
    {
        title: 'withdrawal',
        component: h(Withdrawal),
        value: '1'
    },
]);

const selectedType = ref('deposit');
const activeIndex = ref('0');

onMounted(() => {
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
    });
    if(params.tab === 'withdrawal'){
        selectedType.value = 'withdrawal';
        activeIndex.value = '1';
    } else {
        selectedType.value = 'deposit';
        activeIndex.value = '0';
    }
});
</script>

<template>
<AuthenticatedLayout :title="$t('public.transaction')">
    <div class="flex flex-col gap-5">
        <!-- Tabs -->
        <div class="flex flex-col">
            <Tabs v-model:value="activeIndex">
                <div class="flex flex-col gap-4">
                    <TabList>
                        <Tab v-for="tab in tabs" :key="tab.title" :value="tab.value">
                            {{ $t(`public.${tab.title}`) }}
                        </Tab>
                    </TabList>

                    <TransactionOverview />
                    
                    <TabPanels>
                        <TabPanel v-for="tab in tabs" :key="tab.value" :value="tab.value">
                            <component :is="tab.component" />
                        </TabPanel>
                    </TabPanels>
                </div>
            </Tabs>
        </div>
    </div>
</AuthenticatedLayout>

</template>
