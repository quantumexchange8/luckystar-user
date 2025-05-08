<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { IconAdjustments, IconSearch, IconXboxX } from '@tabler/icons-vue';
import { Card, DataTable, Column, IconField, InputIcon, InputText, Button, Tag } from 'primevue';
import { ref } from 'vue';
import InvestmentListing from './InvestmentListing.vue';

const tableData = ref([
    { 
        join_date: '2025-04-30', 
        account_no: '123455',
        email: 'wong@gmail.com',
        subscription_no: 'SUB123123',
        fund: '$500',
        settlement_period: '30 Days',
        status: 'terminated',
        join_days: '15 Days',
    
    },
    { 
        join_date: '2025-04-30', 
        account_no: '123455',
        email: 'wong@gmail.com',
        subscription_no: 'SUB123123',
        fund: '$500',
        settlement_period: '30 Days',
        status: 'success',
        join_days: '15 Days',
    },
    { 
        join_date: '2025-04-30', 
        account_no: '123455',
        email: 'wong@gmail.com',
        subscription_no: 'SUB123123',
        fund: '$500',
        settlement_period: '30 Days',
        status: 'success',
        join_days: '15 Days',
    },
]);

//status severity
const getSeverity = (status) => {
    switch (status) {
        case 'active':
            return 'success';

        case 'terminated':
            return 'danger';
    }
};
</script>

<template>
    <AuthenticatedLayout :title="$t('public.investment')">
        <div class="flex flex-col gap-5 items-center self-stretch w-full">
            <InvestmentListing />
            <Card class="w-full">
                <template #content>
                    <DataTable
                        :value="tableData"
                        removeableSort
                    >
                        <template #header>
                            <div class="flex flex-col md:flex-row items-center self-stretch gap-3 w-full md:w-auto">
                                <!-- Search bar -->
                                <IconField class="w-full md:w-auto">
                                    <InputIcon>
                                        <IconSearch :size="16" stroke-width="1.5" />
                                    </InputIcon>
                                    <InputText
                                       
                                        :placeholder="$t('public.search_keyword')"
                                        type="text"
                                        class="block w-full pl-10 pr-10"
                                    />
                                    <!-- Clear filter button -->
                                    <div
                                      
                                        class="absolute top-1/2 -translate-y-1/2 right-4 text-surface-300 hover:text-surface-400 select-none cursor-pointer"
                                        @click="clearFilterGlobal"
                                    >
                                        <IconXboxX aria-hidden="true" :size="15" />
                                    </div>
                                </IconField>
    
                                 <!-- filter button -->
                                <Button
                                    class="w-full md:w-28 flex items-center gap-2 dark:text-white text-surface bg-surface-0 dark:bg-surface-800 border-surface-300 dark:border-surface-700 enabled:hover:bg-white dark:hover:bg-surface-800 enabled:hover:border-surface-500 dark:hover:border-surface-600"
                                >
                                    <IconAdjustments :size="20" stroke-width="1.5"/>
                                    {{ $t('public.filter') }}
                                </Button>
                            </div>
                        </template>
    
                        <template>
                            <Column
                                field="join_date"
                                style="min-width: 10rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ 'join date' }}</span>
                                </template>
                                <template #body="{ data }">
                                    <span>{{ data.join_date }}</span>
                                </template>
                            </Column>
    
                            <Column
                                field="account_no"
                                style="min-width: 10rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ 'account no' }}</span>
                                </template>
                                <template #body="{ data }">
                                    {{ data.account_no }}
                                </template>
                            </Column>
    
                            <Column
                                field="subscription_no"
                                 style="min-width: 9rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ 'subscription no' }}</span>
                                </template>
                                <template #body="{ data }">
                                    <span>{{ data.subscription_no }}</span>
                                </template>
                            </Column>
    
                            <Column
                                field="fund"
                                style="min-width: 9rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ 'fund' }}</span>
                                </template>
                                <template #body="{ data }">
                                    <span>{{ data.fund }}</span>
                                </template>
                            </Column>
    
                            <Column
                                field="settlement_period"
                                style="min-width: 9rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ 'settlement_period' }}</span>
                                </template>
                                <template #body="{ data }">
                                    <span>{{ data.settlement_period }}</span>
                                </template>
                            </Column>
    
                            <Column
                                field="join_days"
                                 style="min-width: 9rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ 'join days' }}</span>
                                </template>
                                <template #body="{ data }">
                                    <span>{{ data.join_days }}</span>
                                </template>
                            </Column>
    
                            <Column
                                field="status"
                                 style="min-width: 9rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ 'status' }}</span>
                                </template>
                                <template #body="{ data }">
                                    <Tag :value="data.status" :severity="getSeverity(data.status)"/>
                                </template>
                            </Column>
                        </template>
                    </DataTable>
                </template>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>