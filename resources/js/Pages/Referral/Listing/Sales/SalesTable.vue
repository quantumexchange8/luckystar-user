<script setup>
import { IconAdjustments, IconSearch, IconXboxX } from '@tabler/icons-vue';
import { DataTable, Column, InputText, IconField, InputIcon, Card, Button, Tag } from 'primevue';
import { ref } from 'vue';

const tableData = ref([
    { 
        joined_date: '2025-04-30', 
        downline_name: 'Wong',
        email: 'wong@gmail.com',
        referrer_name: 'Nick',
        level: '1',
        rank: 'member',
        country: 'Malaysia',
        capital_fund: '$2,000',
        status: 'active',
    },
    { 
        joined_date: '2025-04-30', 
        downline_name: 'Wong',
        email: 'wong@gmail.com',
        referrer_name: 'Nick',
        level: '1',
        rank: 'member',
        country: 'Malaysia',
        capital_fund: '$2,000',
        status: 'active',
    },
    { 
        joined_date: '2025-04-30', 
        downline_name: 'Wong',
        email: 'wong@gmail.com',
        referrer_name: 'Nick',
        level: '1',
        rank: 'member',
        country: 'Malaysia',
        capital_fund: '$2,000',
        status: 'inactive',
    },
]);

//status severity
const getSeverity = (status) => {
    switch (status) {
        case 'active':
            return 'success';

        case 'inactive':
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
                            field="joined_date"
                            style="min-width: 10rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'date' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.joined_date }}</span>
                            </template>
                        </Column>

                        <Column
                            field="downline_name"
                            style="min-width: 10rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'client' }}</span>
                            </template>
                            <template #body="{ data }">
                                <div class="flex flex-col">
                                    <span class="text-surface-950 dark:text-white">{{ data.downline_name }}</span>
                                    <span class="text-surface-500">{{ data.email }}</span>
                                </div>
                            </template>
                        </Column>

                        <Column
                            field="referrer_name"
                            style="min-width: 10rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'referrer' }}</span>
                            </template>
                            <template #body="{ data }">
                                {{ data.referrer_name }}
                            </template>
                        </Column>

                        <Column
                            field="level"
                            style="min-width: 9rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'level' }}</span>
                            </template>
                            <template #body="{ data }">
                               {{ data.level }}
                            </template>
                        </Column>

                        <Column
                            field="rank"
                            style="min-width: 9rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'rank' }}</span>
                            </template>
                            <template #body="{ data }">
                                {{ data.rank }}
                            </template>
                        </Column>

                        <Column
                            field="country"
                            style="min-width: 9rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'country' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.country }}</span>
                            </template>
                        </Column>

                        <Column
                            field="capital_fund"
                            style="min-width: 9rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'capital fund' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.capital_fund }}</span>
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
            </div>
        </template>
    </Card>
</template>
