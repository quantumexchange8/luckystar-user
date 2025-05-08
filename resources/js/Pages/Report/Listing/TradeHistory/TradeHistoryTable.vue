<script setup>
import { IconAdjustments, IconSearch, IconXboxX } from '@tabler/icons-vue';
import { DataTable, Column, InputText, IconField, InputIcon, Card, Button, Tag } from 'primevue';
import { ref } from 'vue';

const tableData = ref([
    { 
        created_at: '2025-04-30', 
        name: 'Wong',
        email: 'wong@gmail.com',
        close_time: '8:30pm',
        close_date: '2025-05-20',
        symbol: 'GBPUSDm',
        volume: '0.01',
        trade_net_profit: '$0.05',
    
    },
    { 
        created_at: '2025-04-30', 
        name: 'Wong',
        email: 'wong@gmail.com',
        close_time: '8:30pm',
        close_date: '2025-05-20',
        symbol: 'GBPUSDm',
        volume: '0.01',
        trade_net_profit: '$0.05',
    },
    { 
        created_at: '2025-04-30', 
        name: 'Wong',
        email: 'wong@gmail.com',
        close_time: '8:30pm',
        close_date: '2025-05-20',
        symbol: 'GBPUSDm',
        volume: '0.01',
        trade_net_profit: '$0.05',
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
                            field="created_at"
                            style="min-width: 10rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'date' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.created_at }}</span>
                            </template>
                        </Column>

                        <Column
                            field="name"
                            style="min-width: 10rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'name' }}</span>
                            </template>
                            <template #body="{ data }">
                                <div class="flex flex-col">
                                    <span class="text-surface-950 dark:text-white">{{ data.name }}</span>
                                    <span class="text-surface-500">{{ data.email }}</span>
                                </div>
                            </template>
                        </Column>

                        <Column
                            field="close_time"
                            style="min-width: 9rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'close time' }}</span>
                            </template>
                            <template #body="{ data }">
                                <div class="flex flex-col">
                                    <span class="text-surface-950 dark:text-white font-medium">{{ data.close_date }}</span>
                                    <span class="text-surface-500 text-xs">{{ data.close_time }}</span>
                                </div>
                            </template>
                        </Column>

                        <Column
                            field="symbol"
                            style="min-width: 9rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'symbol' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.symbol }}</span>
                            </template>
                        </Column>

                        <Column
                            field="volume"
                            style="min-width: 9rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'volume' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.volume }}</span>
                            </template>
                        </Column>

                        <Column
                            field="trade_net_profit"
                             style="min-width: 9rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'trade net profit' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.trade_net_profit }}</span>
                            </template>
                        </Column>
                    </template>
                </DataTable>
            </div>
        </template>
    </Card>
</template>
