<script setup>
import { IconAdjustments, IconSearch, IconXboxX } from '@tabler/icons-vue';
import { DataTable, Column, InputText, IconField, InputIcon, Card, Button, Tag } from 'primevue';
import { ref } from 'vue';

const tableData = ref([
    { 
        approved_at: '2025-04-30', 
        name: 'Wong',
        upline: 'Sam',
        transaction_number: 'TXN00000001',
        to_wallet_id: 'Bonus Wallet',
        status: 'rejected',
        amount: '$2332',
    
    },
    { 
        approved_at: '2025-04-30', 
        name: 'Wong',
        upline: 'Sam',
        transaction_number: 'TXN00000001',
        to_wallet_id: 'Bonus Wallet',
        status: 'success',
        amount: '$2332',
    },
    { 
        approved_at: '2025-04-30', 
        name: 'Wong',
        upline: 'Sam',
        transaction_number: 'TXN00000001',
        to_wallet_id: 'Bonus Wallet',
        status: 'pending',
        amount: '$2332',
    },
]);

//status severity
const getSeverity = (status) => {
    switch (status) {
        case 'success':
            return 'success';

        case 'pending':
            return 'info';

        case 'rejected':
            return 'danger';
    }
};
</script>

<template>
    <Card class="w-full">
        <template #content>
            <div class="w-full">
                <DataTable
                    :value="tableData"
                    removableSort
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
                            field="approved_at"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'approved at' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.approved_at }}</span>
                            </template>
                        </Column>

                        <Column
                            field="name"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'name' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.name }}</span>
                            </template>
                        </Column>

                        <Column
                            field="upline"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'upline' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.upline }}</span>
                            </template>
                        </Column>

                        <Column
                            field="transaction_number"
                            style="min-width: 16rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'transaction number' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.transaction_number }}</span>
                            </template>
                        </Column>

                        <Column
                            field="to_wallet_id"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'to' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.to_wallet_id }}</span>
                            </template>
                        </Column>

                        <Column
                            field="amount"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'amount' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.amount }}</span>
                            </template>
                        </Column>

                        <Column
                            field="status"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'status' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>
                                    <Tag :value="data.status" :severity="getSeverity(data.status)"/>
                                </span>
                            </template>
                        </Column>
                    </template>
                </DataTable>
            </div>
        </template>
    </Card>
</template>
