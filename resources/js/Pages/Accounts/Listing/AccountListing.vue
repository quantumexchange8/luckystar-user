<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AccountHeader from './AccountHeader.vue';
import { Tab, TabList, TabPanels, Tabs } from 'primevue';
import { onMounted, ref } from 'vue';

const tabs = ref([
    {
        title: 'finance',
       
        value: '0'
    },
    {
        title: 'investment',
       
        value: '1'
    },
    {
        title: 'history',
        value: '2'
    },
]);

const selectedType = ref('finance');
const activeIndex = ref('0');

onMounted(() => {
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
    });
    if(params.tab === 'history'){
        selectedType.value = 'history';
        activeIndex.value = '2';
    } else if(params.tab === 'investment'){
        selectedType.value = 'investment';
        activeIndex.value = '1';
    } else {
        selectedType.value = 'finance';
        activeIndex.value = '0';
    }
});
</script>

<template>
    <AuthenticatedLayout title="Accounts">
        <div class="flex flex-col gap-5">
            <div class="flex flex-col gap-5 items-center self-stretch w-full">
                <AccountHeader />
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