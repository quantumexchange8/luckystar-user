<script  setup>
import { IconCalendarCheck, IconCalendarCancel, IconUserStar } from '@tabler/icons-vue';
import { Card, Button, Tag, DataTable, Column } from 'primevue';
import { ref } from 'vue';

const dummy = ref([
    {
        profit: '2.00',
        amount: '5.00',
    },
       {
        profit: '2.00',
        amount: '5.00',
    },
       {
        profit: '2.00',
        amount: '5.00',
    },
       {
        profit: '2.00',
        amount: '5.00',
    },
       {
        profit: '2.00',
        amount: '5.00',
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
                        style="min-width: 5rem"
                    >
                        <template #header>
                            <span class="block">{{ $t('public.amount') }}</span>
                        </template>

                        <template #body="{ data }">
                            {{ data.amount }}
                        </template>
                        
                    </Column>

                </DataTable>
            </div>
        </template>
    </Card>
</template>