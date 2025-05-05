<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AccountHeader from './AccountHeader.vue';
import { Tab, TabList, TabPanels, Tabs, TabPanel } from 'primevue';
import { onMounted, ref, h } from 'vue';
import IndividualView from './Individual/IndividualView.vue';
import ManagedView from './Managed/ManagedView.vue';
import DemoView from './Demo/DemoView.vue';

defineProps({
    accountTypes: Array,
    leverageOptions: Array,
})

const tabs = ref([
    {
        title: 'Individual',
        component: h(IndividualView),
        value: '0'
    },
    {
        title: 'Managed',
        component: h(ManagedView),
        value: '1'
    },
    {
        title: 'Demo',
        component: h(DemoView),
        value: '2'
    },
]);

const selectedType = ref('Individual');
const activeIndex = ref('0');

onMounted(() => {
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
    });
    if(params.tab === 'Demo'){
        selectedType.value = 'Demo';
        activeIndex.value = '2';
    } else if(params.tab === 'Managed'){
        selectedType.value = 'Managed';
        activeIndex.value = '1';
    } else {
        selectedType.value = 'Individual';
        activeIndex.value = '0';
    }
});
</script>

<template>
    <AuthenticatedLayout title="Accounts">
        <div class="flex flex-col gap-5">
            <div class="flex flex-col gap-5 items-center self-stretch w-full">
                <AccountHeader
                    :accountTypes="accountTypes"
                    :leverageOptions="leverageOptions"
                />
            </div>

            <!-- Tabs -->
            <div class="flex flex-col">
                <Tabs v-model:value="activeIndex">
                    <TabList>
                        <Tab v-for="tab in tabs" :key="tab.title" :value="tab.value">{{ tab.title }}
                        </Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel v-for="tab in tabs" :key="tab.value" :value="tab.value">
                            <component :is="tab.component" />
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
