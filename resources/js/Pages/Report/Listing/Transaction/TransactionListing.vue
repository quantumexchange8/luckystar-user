<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Tab, TabList, TabPanels, Tabs, TabPanel } from 'primevue';
import { onMounted, ref, h, watch } from 'vue';
import TransactionOverview from './TransactionOverview.vue';
import CashWallet from './Wallet/CashWallet.vue';
import BonusWallet from './Wallet/BonusWallet.vue';

const successAmount = ref(null);
const failAmount = ref(null);
const totalAmount = ref(null)

const handleOverview = (data) => {
    successAmount.value = Number(data.successAmount);
    failAmount.value = Number(data.failAmount);
    totalAmount.value = Number(data.totalAmount)
}

const tabs = ref([
    {
        title: 'cash_wallet',
        component: h(CashWallet),
        value: '0'
    },
    {
        title: 'bonus_wallet',
        component: h(BonusWallet),
        value: '1'
    },
]);

const selectedType = ref('cash_wallet');
const activeIndex = ref('0');

onMounted(() => {
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
    });
    if(params.tab === 'bonus_wallet'){
        selectedType.value = 'bonus_wallet';
        activeIndex.value = '1';
    } else {
        selectedType.value = 'cash_wallet';
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
                <div class="flex flex-col gap-5">
                    <TabList>
                        <Tab v-for="tab in tabs" :key="tab.title" :value="tab.value">
                            {{ $t(`public.${tab.title}`) }}
                        </Tab>
                    </TabList>

                    <TransactionOverview 
                        :successAmount="successAmount"
                        :failAmount="failAmount"
                        :totalAmount="totalAmount"
                    />
                    
                    <TabPanels class="pt-0">
                        <TabPanel v-for="tab in tabs" :key="tab.value" :value="tab.value">
                            <component 
                                :is="tabs[activeIndex]?.component"
                                @updateTransactionOverview="handleOverview"
                            />
                        </TabPanel>
                    </TabPanels>
                </div>
            </Tabs>
        </div>
    </div>
</AuthenticatedLayout>

</template>
