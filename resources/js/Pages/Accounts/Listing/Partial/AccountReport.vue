<script setup>
import { IconX } from '@tabler/icons-vue';
import { DataTable, Select, DatePicker, Column } from 'primevue';
import { ref, watch } from 'vue';

//Date Filter
const selectedDate = ref([]);

const clearDate = () => {
    selectedDate.value = [];
};

// sort
const selectedSort = ref('All');

const sortOptions = ref([
    'All',
    'Deposit',
    'Withdrawal',
    'Transfer',
]);

const tableData = ref([
    { created_at: '2025-04-30', desc: 'Deposit', amount: 500 },
    { created_at: '2025-04-29', desc: 'Withdrawal', amount: -200 },
    { created_at: '2025-04-28', desc: 'Transfer', amount: 100 },
]);

</script>

<template>
    <div class="w-full">
        <DataTable
            :value="tableData"
            removableSort
        >
            <template #header>
                <div class="flex flex-col gap-3 items-center self-stretch">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 w-full">
                        <div class="flex flex-col gap-1 items-start self-stretch">
                            <div class="relative w-full">
                                <DatePicker
                                    v-model="selectedDate"
                                    dateFormat="dd/mm/yy"
                                    selectionMode="range"
                                    placeholder="dd/mm/yyyy - dd/mm/yyyy"
                                    class="w-full"
                                />
                                <div
                                    v-if="selectedDate && selectedDate.length > 0"
                                    class="absolute top-2/4 -mt-2 right-2 text-surface-400 select-none cursor-pointer bg-transparent"
                                    @click="clearDate"
                                >
                                    <IconX :size="15" strokeWidth="1.5"/>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1 items-start self-stretch">
                            <Select
                                v-model="selectedSort"
                                :options="sortOptions"
                                class="w-full"
                            >
                            <template #value="slotProps">
                                <div v-if="slotProps.value" class="flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <div>{{ slotProps.value }}</div>
                                    </div>
                                </div>
                            </template>

                            <template #option="slotProps">
                                {{ slotProps.option }}
                            </template>
                            </Select>
                        </div>
                    </div>
                </div>
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
                    field="desc"
                    style="min-width: 12rem"
                  
                >
                    <template #header>
                        <span class="block">{{ 'Description' }}</span>
                    </template>
                    <template #body="{ data }">
                        <span>{{ data.desc }}</span>
                    </template>
                </Column>

                <Column
                    field="amount"
                    sortable
                  
                >
                    <template #header>
                        <span class="block">{{ 'Amount ($)' }}</span>
                    </template>
                    <template #body="{ data }">
                        <span :class="data.amount >= 0 ? 'text-green-500' : 'text-red-500'">
                            {{ data.amount.toFixed(2) }}
                        </span>
                    </template>
                </Column>
            </template>
        </DataTable>
    </div>
</template>