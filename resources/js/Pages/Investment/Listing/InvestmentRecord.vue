<script  setup>
import { Card, Tag, DataTable, Column } from 'primevue';
import { ref } from 'vue';

const dummy = ref([
    {
        date: '2025-12-10',
        profit: '2.00',
        amount: '5.00',
        status: 'claimed'
    },
    {
        date: '2025-12-10',
        profit: '2.00',
        amount: '5.00',
        status: 'claimed'
    },
    {
        date: '2025-12-10',
        profit: '2.00',
        amount: '5.00',
        status: 'claimed'
    },
    {
        date: '2025-12-10',
        profit: '2.00',
        amount: '5.00',
        status: 'claimed'
    },
    {
        date: '2025-12-10',
        profit: '2.00',
        amount: '5.00',
        status: 'claimed'
    },
])
</script>

<template>
    <Card class="w-full h-full">
        <template #content>
            <div class="w-full">
                <DataTable
                    :value="dummy"
                >
                    <Column
                        field="date"
                        style="min-width: 5rem"
                    >
                        <template #header>
                            <span class="block">{{ $t('public.date') }}</span>
                        </template>

                        <template #body="{ data }">
                            {{ data.date }}
                        </template>
                        
                    </Column>

                    <Column
                        field="profit"
                        style="min-width: 5rem"
                    >
                        <template #header>
                            <span class="block">{{ $t('public.profit') }}</span>
                        </template>

                        <template #body="{ data }">
                            {{ data.profit }}
                        </template>
                        
                    </Column>

                    <Column
                        field="amount"
                        style="min-width: 3rem"
                    >
                        <template #header>
                            <span class="block">{{ $t('public.amount') }}</span>
                        </template>

                        <template #body="{ data }">
                            {{ data.amount }}
                        </template>
                    </Column>

                    <Column
                        field="status"
                    >
                        <template #header>
                            <span class="block">{{ $t('public.status') }}</span>
                        </template>

                        <template #body="{ data }">
                            <Tag 
                                severity="success"
                                :value="$t(`public.${data.status}`)"
                            />
                        </template>
                    </Column>

                </DataTable>
            </div>
        </template>
    </Card>
</template>