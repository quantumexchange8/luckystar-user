<script setup>
import { Card, DataTable, Column } from 'primevue';
import { ref } from 'vue';

const tableData = ref([
    { 
        created_at: '2025-04-30', 
        symbol: 'Deposit', 
        ticket: '24234',
        action: 'BUY',
        volume: '33.33',
        open_price: '$2312',
        close_price: '$2332',
        swap: '0.00',
        profit: 500 ,
        change: '0.00%'
    },
    { 
        created_at: '2025-04-30', 
        symbol: 'Deposit', 
        ticket: '24234',
        action: 'BUY',
        volume: '33.33',
        open_price: '$2312',
        close_price: '$2332',
        swap: '0.00',
        profit: 500 ,
        change: '0.00%'
    },
    { 
        created_at: '2025-04-30', 
        symbol: 'Deposit', 
        ticket: '24234',
        action: 'BUY',
        volume: '33.33',
        open_price: '$2312',
        close_price: '$2332',
        swap: '0.00',
        profit: 500 ,
        change: '0.00%'
    },
]);
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
                        Portfolio Performance
                    </template>
                
                    <template>
                        <Column
                            field="created_at"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'Date' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.created_at }}</span>
                            </template>
                        </Column>

                        <Column
                            field="symbol"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'Symbol' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.symbol }}</span>
                            </template>
                        </Column>

                        <Column
                            field="ticket"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'Ticket' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.ticket }}</span>
                            </template>
                        </Column>

                        <Column
                            field="action"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'Action' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.action }}</span>
                            </template>
                        </Column>

                        <Column
                            field="volume"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'Volume' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.volume }}</span>
                            </template>
                        </Column>

                        <Column
                            field="open_price"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'Open Price' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.open_price }}</span>
                            </template>
                        </Column>

                        <Column
                            field="close_price"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'Close Price' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.close_price }}</span>
                            </template>
                        </Column>

                        <Column
                            field="swap"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'Swap' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.swap }}</span>
                            </template>
                        </Column>
    
                        <Column
                            field="profit"
                            sortable
                            style="min-width: 12rem"
                        >
                            <template #header>
                                <span class="block">{{ 'Profit ($)' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span :class="data.profit >= 0 ? 'text-green-500' : 'text-red-500'">
                                    {{ data.profit.toFixed(2) }}
                                </span>
                            </template>
                        </Column>

                        <Column
                            field="change"
                            style="min-width: 12rem"
                        
                        >
                            <template #header>
                                <span class="block">{{ 'Change' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.change }}</span>
                            </template>
                        </Column>
                    </template>
                </DataTable>
            </div>
        </template>
    </Card>
</template>