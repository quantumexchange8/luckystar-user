<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { IconAdjustments, IconSearch, IconXboxX, IconX } from '@tabler/icons-vue';
import { Card, DataTable, Column, IconField, InputIcon, InputText, Button, Tag, ProgressSpinner, Popover, Select, DatePicker } from 'primevue';
import {FilterMatchMode} from "@primevue/core/api";
import debounce from "lodash/debounce.js";
import { ref, watch } from 'vue';
import InvestmentListing from './InvestmentListing.vue';
import InvestmentView from './InvestmentView.vue';
import InvestmentRecord from './InvestmentRecord.vue';
import {generalFormat} from "@/Composables/format.js";
import dayjs from 'dayjs';
import EmptyData from '@/Components/EmptyData.vue';
import SubscriptionInfo from './Detail/SubscriptionInfo.vue';

const props = defineProps({
    subscribedStrategyCount: Number,
});

const isLoading = ref(false);
const dt = ref(null);
const subscribedStrategy = ref([]);
const {formatAmount} = generalFormat();
const totalRecords = ref(0);
const first = ref(0);
const strategy = ref('');

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    meta_login: { value: strategy.value.meta_login, matchMode: FilterMatchMode.EQUALS },
    master_meta_login: { value: strategy.value.meta_login, matchMode: FilterMatchMode.EQUALS },
    start_join_date: { value: null, matchMode: FilterMatchMode.EQUALS },
    end_join_date: { value: null, matchMode: FilterMatchMode.EQUALS },
    status: { value: null, matchMode: FilterMatchMode.EQUALS },
});

const lazyParams = ref({});

const loadLazyData = (event) => {
    isLoading.value = true;

    lazyParams.value = { ...lazyParams.value, first: event?.first || first.value };
    lazyParams.value.filters = filters.value;
    try {
        setTimeout(async () => {
            const params = {
                page: JSON.stringify(event?.page + 1),
                sortField: event?.sortField,
                sortOrder: event?.sortOrder,
                include: [],
                lazyEvent: JSON.stringify(lazyParams.value)
            };

            const url = route('investment.subscribedStrategyData', params);
            const response = await fetch(url);
            const results = await response.json();

            subscribedStrategy.value = results?.data?.data;
            console.log(subscribedStrategy.value)
          
            totalRecords.value = results?.data?.total;
            isLoading.value = false;

        }, 100);
    }  catch (e) {
        subscribedStrategy.value = [];
        totalRecords.value = 0;
        isLoading.value = false;
    }
};

const onPage = (event) => {
    lazyParams.value = event;
    loadLazyData(event);
};
const onSort = (event) => {
    lazyParams.value = event;
    loadLazyData(event);
};
const onFilter = (event) => {
    lazyParams.value.filters = filters.value ;
    loadLazyData(event);
};

// date filter
const selectedDate = ref([]);

const clearDate = () => {
    selectedDate.value = [];
}

watch(selectedDate, (newDateRange) => {
    if (Array.isArray(newDateRange)) {
        const [startDate, endDate] = newDateRange;
        filters.value['start_join_date'].value = startDate;
        filters.value['end_join_date'].value = endDate;

        if (startDate !== null && endDate !== null) {
            loadLazyData();
        }
    } else {
        console.warn('Invalid date range format:', newDateRange);
    }
})

//filter status
const status = ref(['active','terminated']);

//filter toggle
const op = ref();
const toggle = (event) => {
    op.value.toggle(event);
};

watch(strategy, (newStrategy) => {
    filters.value.meta_login = newStrategy.meta_login;
    filters.value.master_meta_login = newStrategy.master_meta_login;

    lazyParams.value = {
        first: dt.value.first,
        rows: dt.value.rows,
        sortField: null,
        sortOrder: null,
        filters: filters.value
    };

    loadLazyData();
})

watch(
    filters.value['global'],
    debounce(() => {
        loadLazyData();
    }, 300)
);

watch([filters.value['status']], () => {
    loadLazyData()
});

const clearAll = () => {
    filters.value['global'].value = null;
    filters.value['start_join_date'].value = null;
    filters.value['end_join_date'].value = null;
    filters.value['status'].value = null;
    selectedDate.value = [];
};

const clearFilterGlobal = () => {
    filters.value['global'].value = null;
}

//status severity
const getSeverity = (status) => {
    switch (status) {
        case 'active':
            return 'success';
        
        case 'pending':
            return 'info';

        case 'terminated':
            return 'danger';
    }
};
</script>

<template>
    <AuthenticatedLayout :title="$t('public.investment')">
        <div v-if="subscribedStrategyCount === 0">
           <EmptyData
                :title="$t('public.no_subscription_record')"
            />
        </div>

        <div v-else class="flex flex-col gap-5 items-stretch self-stretch w-full">
           <div class="flex flex-col md:flex-row items-stretch gap-5">
                <div class="flex flex-col gap-5 w-full md:w-1/2">
                    <div class="overflow-x-auto">
                        <div class="min-w-max">
                            <InvestmentListing 
                                :subscribedStrategyCount="subscribedStrategyCount"
                                @update:strategy="strategy = $event"
                            />
                        </div>
                    </div>

                    <InvestmentView 
                        :subscribedStrategy="subscribedStrategy"
                        :isLoading="isLoading"
                    />
                </div>

                <div class="w-full md:w-1/2">
                    <InvestmentRecord 
                        :subscribedStrategy="subscribedStrategy"
                    />
                </div>
            </div>

            <Card class="w-full">
                <template #content>
                    <DataTable
                        :value="subscribedStrategy"
                        lazy
                        paginator
                        removableSort
                        :rows="10"
                        :rowsPerPageOptions="[10, 20, 50, 100]"
                        :first="first"
                        paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
                        :currentPageReportTemplate="$t('public.paginator_caption')"
                        v-model:filters="filters"
                        ref="dt"
                        dataKey="id"
                        :loading="isLoading"
                        :totalRecords="totalRecords"
                        @page="onPage($event)"
                        @sort="onSort($event)"
                        @filter="onFilter($event)"
                        :globalFilterFields="['meta_login', 'subscription_number']"
                    >
                        <template #header>
                            <div class="flex flex-col md:flex-row items-center self-stretch gap-3 w-full md:w-auto">
                                <!-- Search bar -->
                                <IconField class="w-full md:w-auto">
                                    <InputIcon>
                                        <IconSearch :size="16" stroke-width="1.5" />
                                    </InputIcon>
                                    <InputText
                                        v-model="filters['global'].value"
                                        :placeholder="$t('public.search_keyword')"
                                        type="text"
                                        class="block w-full pl-10 pr-10"
                                    />
                                    <!-- Clear filter button -->
                                    <div
                                        v-if="filters['global'].value"
                                        class="absolute top-1/2 -translate-y-1/2 right-4 text-surface-300 hover:text-surface-400 select-none cursor-pointer"
                                        @click="clearFilterGlobal"
                                    >
                                        <IconXboxX aria-hidden="true" :size="15" />
                                    </div>
                                </IconField>
    
                                 <!-- filter button -->
                                <Button
                                    class="w-full md:w-28 flex items-center gap-2 dark:text-white text-surface bg-surface-0 dark:bg-surface-800 border-surface-300 dark:border-surface-700 enabled:hover:bg-white dark:hover:bg-surface-800 enabled:hover:border-surface-500 dark:hover:border-surface-600"
                                    @click="toggle"
                                >
                                    <IconAdjustments :size="20" stroke-width="1.5"/>
                                    {{ $t('public.filter') }}
                                </Button>
                            </div>
                        </template>

                        <template #empty>
                            <div v-if="subscribedStrategyCount === 0">
                                <EmptyData
                                    :title="$t('public.no_subscription_record')"
                                />
                            </div>
                        </template>

                        <template #loading>
                            <div class="flex flex-col gap-2 items-center justify-center">
                                <ProgressSpinner
                                    strokeWidth="4"
                                />
                                <!-- <span v-if="exportStatus === 'true'" class="text-sm text-surface-700 dark:text-surface-300">{{ $t('public.export_caption') }}</span> -->
                                <span class="text-sm text-surface-700 dark:text-surface-300">{{ $t('public.subscription_loading_caption') }}</span>
                            </div>
                        </template>
    
                        <template v-if="subscribedStrategy?.length > 0">
                            <Column
                                field="approval_at"
                                style="min-width: 10rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ $t('public.join_date') }}</span>
                                </template>
                                <template #body="{ data }">
                                     <span v-if="dayjs(data.approval_at).isValid()">
                                        {{ dayjs(data.approval_at).format('YYYY-MM-DD') }}
                                        <div class="text-xs text-surface-500 mt-1">
                                            {{ dayjs(data.approval_at).add(8, 'hour').format('hh:mm:ss A') }}
                                        </div>
                                    </span>

                                    <span v-else>-</span>
                                </template>
                            </Column>
    
                            <Column
                                field="meta_login"
                                style="min-width: 10rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ $t('public.account_no') }}</span>
                                </template>
                                <template #body="{ data }">
                                    {{ data.meta_login }}
                                </template>
                            </Column>
    
                            <Column
                                field="subscription_number"
                                style="min-width: 9rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ $t('public.subscription_no') }}</span>
                                </template>
                                <template #body="{ data }">
                                    <span>{{ data.subscription_number }}</span>
                                </template>
                            </Column>

                            <Column
                                field="subscription_amount"
                                style="min-width: 9rem"
                                sortable
                            
                            >
                                <template #header>
                                    <span class="block">{{ 'fund' }}</span>
                                </template>
                                <template #body="{ data }">
                                    <span>{{ formatAmount(data.subscription_amount, 2) }}</span>
                                </template>
                            </Column>
    
                            <Column
                                field="settlement_period"
                                style="min-width: 9rem"
                                
                            
                            >
                                <template #header>
                                    <span class="block">{{ $t('public.settlement_period') }}</span>
                                </template>
                                <template #body="{ data }">
                                    <span>{{ data.settlement_period }} {{ $t(`public.${data.settlement_period_type}`) }}</span>
                                </template>
                            </Column>
    
                            <Column
                                field="join_days"
                                style="min-width: 5rem"
                            >
                                <template #header>
                                    <span class="block">{{ $t('public.join_days') }}</span>
                                </template>
                                <template #body="{ data }">
                                    <span>{{  }}</span>
                                </template>
                            </Column>
    
                            <Column
                                field="status"
                                style="min-width: 5rem"
                        
                            >
                                <template #header>
                                    <span class="block">{{ $t('public.status') }}</span>
                                </template>
                                <template #body="{ data }">
                                    <Tag :value="$t(`public.${data.status}`)" :severity="getSeverity(data.status)"/>
                                </template>
                            </Column>

                            <Column
                                field="terminated_at"
                                style="min-width: 5rem"
                        
                            >
                                <template #header>
                                    <span class="block">{{ $t('public.terminated_at') }}</span>
                                </template>
                                <template #body="{ data }">
                                     <span v-if="dayjs(data.terminated_at).isValid()">
                                        {{ dayjs(data.terminated_at).format('YYYY-MM-DD') }}
                                        <div class="text-xs text-surface-500 mt-1">
                                            {{ dayjs(data.terminated_at).add(8, 'hour').format('hh:mm:ss A') }}
                                        </div>
                                    </span>

                                    <span v-else>-</span>
                                </template>
                            </Column>

                            <Column
                                field="action"
                                frozen
                                alignFrozen="right"
                                style="width: 5%"
                            >
                                <template #body="{data}">
                                    <SubscriptionInfo 
                                        :subscription="data"
                                    />
                                </template>
                            </Column>
                        </template>
                    </DataTable>
                </template>
            </Card>

            <Popover ref="op">
                <div class="flex flex-col gap-6 w-60">
                    <!-- Filter Date -->
                    <div class="flex flex-col gap-2 items-center self-stretch">
                        <div class="flex self-stretch text-sm text-surface-ground dark:text-white">
                            {{ $t('public.filter_by_date') }}
                        </div>
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

                    <!-- Filter status -->
                    <div class="flex flex-col gap-2 items-center self-stretch">
                        <div class="flex self-stretch text-sm text-surface-ground dark:text-white">
                            {{ $t('public.filter_by_status') }}
                        </div>
                        <Select
                            v-model="filters['status'].value"
                            :options="status"
                            :placeholder="$t('public.select_status')"
                            class="w-full"
                            showClear
                        >
                            <template #value="slotProps">
                                <div v-if="slotProps.value" class="flex items-center">
                                    {{ $t(`public.${slotProps.value}`) }}
                                </div>
                                <span v-else>{{ slotProps.placeholder }}</span>
                            </template>

                            <template #option="slotProps">
                                <Tag :value="$t(`public.${slotProps.option}`)" :severity="getSeverity(slotProps.option)" />
                            </template>
                        </Select>
                    </div>

                    <Button
                        type="button"
                        outlined
                        class="w-full"
                        @click="clearAll"
                    >
                        {{ $t('public.clear_all') }}
                    </Button>
                </div>
            </Popover>
        </div>
    </AuthenticatedLayout>
</template>