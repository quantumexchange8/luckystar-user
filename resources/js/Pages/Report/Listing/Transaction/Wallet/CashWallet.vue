<script setup>
import { IconAdjustments, IconSearch, IconXboxX, IconX } from '@tabler/icons-vue';
import { DataTable, Column, InputText, IconField, InputIcon, Card, Button, Tag, Popover, DatePicker, ProgressSpinner, Select } from 'primevue';
import { onMounted, ref, watch, watchEffect } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { generalFormat } from '@/Composables/format';
import debounce from "lodash/debounce.js";
import dayjs from 'dayjs';
import { usePage } from '@inertiajs/vue3';
import EmptyData from '@/Components/EmptyData.vue';

const isLoading = ref(false);
const dt = ref(null);
const first = ref(0);
const cashWallet = ref([]);
const totalRecords = ref(0);
const cashWalletCounts = ref();
const successAmount = ref();
const failAmount = ref();
const totalAmount = ref();
const {formatAmount} = generalFormat();

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    start_date: { value: null, matchMode: FilterMatchMode.EQUALS },
    end_date: { value: null, matchMode: FilterMatchMode.EQUALS },
    status: { value: null, matchMode: FilterMatchMode.EQUALS },
    type: { value: null, matchMode: FilterMatchMode.EQUALS },
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
                lazyEvent: JSON.stringify(lazyParams.value), 
            };
    
            const url = route('transaction.getCashWalletData', params);
            const response = await fetch(url);
    
            const results = await response.json();
            cashWallet.value = results?.data?.data;
            totalRecords.value = results?.data?.total;
            cashWalletCounts.value = results?.cashWalletCounts;
            successAmount.value = results?.successAmount;
            failAmount.value = results?.failAmount;
            totalAmount.value = results?.totalAmount;
            isLoading.value = false;
        }, 100);

    } catch (e) {
        cashWallet.value = [];
        totalRecords.value = 0;
        isLoading.value = false;
    }
}

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

const emit = defineEmits(['updateTransactionOverview']);

// Emit the totals whenever they change
watch([successAmount, failAmount, totalAmount], () => {
    emit('updateTransactionOverview', {
        successAmount: successAmount.value,
        failAmount: failAmount.value,
        totalAmount: totalAmount.value,
    });
});

//Date Filter
const selectedDate = ref([]);

const clearDate = () => {
    selectedDate.value = [];
};

watch(selectedDate, (newDateRange) => {
    if(Array.isArray(newDateRange)) { //ensure is an array with both start and end date
        const [startDate, endDate] = newDateRange; // extract data from newDateRange
        filters.value['start_date'].value = startDate; //update new date selected
        filters.value['end_date'].value = endDate;

        if(startDate !== null && endDate !== null){
            loadLazyData();
        }
    } else {
        console.warn('Invalid date range format:', newDateRange);
    }
});

//filter status
const status = ref(['success','rejected', 'failed']);

// filter transaction type
const type = ref(['top_up', 'deposit', 'withdrawal'])

//filter toggle
const op = ref();
const toggle = (event) => {
    op.value.toggle(event);
};

onMounted(() => {
    lazyParams.value = {
        first: dt.value.first,
        rows: dt.value.rows,
        sortField: null,
        sortOrder: null,
        filters: filters.value
    };
    loadLazyData();
});

watch(
    filters.value['global'],
    debounce(() => {
        loadLazyData();
    }, 300)
)

watch([filters.value['status'], filters.value['type']], () => {
    loadLazyData()
});

const clearAll = () => {
    filters.value['global'].value = null;
    filters.value['start_date'].value = null;
    filters.value['end_date'].value = null;
    filters.value['status'].value = null;
    filters.value['type'].value = null;
    selectedDate.value = [];
};

const clearFilterGlobal = () => {
    filters.value['global'].value = null;
};

//status severity
const getSeverity = (status) => {
    switch (status) {
        case 'success':
            return 'success';

        case 'processing':
            return 'info';

        case 'failed':
            return 'danger';

        case 'rejected':
            return 'danger';
    }
};

watchEffect(() => {
    if (usePage().props.toast !== null) {
        loadLazyData();
    }
});
</script>

<template>
    <Card class="w-full">
        <template #content>
            <div class="w-full">
                <DataTable
                    :value="cashWallet"
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
                    :globalFilterFields="['name' ,'transaction_number']"
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
                        <div v-if="cashWalletCounts === 0">
                            <EmptyData
                                :title="$t('public.no_transaction_record')"
                            />
                        </div>
                    </template>

                    <template #loading>
                        <div class="flex flex-col gap-2 items-center justify-center">
                            <ProgressSpinner
                                strokeWidth="4"
                            />
                            <!-- <span v-if="exportStatus === 'true'" class="text-sm text-surface-700 dark:text-surface-300">{{ $t('public.export_caption') }}</span> -->
                            <span class="text-sm text-surface-700 dark:text-surface-300">{{ $t('public.transaction_loading_caption') }}</span>
                        </div>
                    </template>

                    <template v-if="cashWallet?.length > 0">
                        <Column
                            field="approval_at"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ $t('public.approved_at') }}</span>
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
                            field="user_id"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ $t('public.name') }}</span>
                            </template>
                            <template #body="{ data }">
                                <div class="flex flex-col">
                                    <span class="text-surface-950 dark:text-white">{{ data.user.first_name }} {{ data.user.last_name }}</span>
                                    <span class="text-surface-500 text-xs">{{ data.user.email }}</span>
                                </div>
                            </template>
                        </Column>

                        <Column
                            field="upline"
                            style="min-width: 13rem"
                        >
                            <template #header>
                                <span class="block">{{ $t('public.upline') }}</span>
                            </template>
                            <template #body="{ data }">
                                <div
                                    v-if="data.user.upline_id"
                                    class="flex flex-col"
                                >
                                    <span class="text-surface-950 dark:text-white">{{ data.user.upline.first_name }} {{ data.user.upline.last_name }}</span>
                                    <span class="text-surface-500 text-xs">{{ data.user.upline.email }}</span>
                                </div>
                                <div v-else>
                                    -
                                </div>
                            </template>
                        </Column>

                        <Column
                            field="transaction_number"
                            style="min-width: 16rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ $t('public.transaction_number') }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.transaction_number }}</span>
                            </template>
                        </Column>

                        <Column
                            field="from_wallet_id"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ $t('public.from') }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.from_wallet?.type ? $t(`public.${data.from_wallet.type}`) : 'ttpay' }}</span>
                            </template>
                        </Column>

                        <Column
                            field="to_wallet_id"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ $t('public.to') }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ data.to_wallet?.type ? $t(`public.${data.to_wallet.type}`) : '-' }}</span>
                            </template>
                        </Column>

                        <Column
                            field="transaction_type"
                            style="min-width: 12rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ $t('public.transaction_type') }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>{{ $t(`public.${data.transaction_type}`) }}</span>
                            </template>
                        </Column>

                        <Column
                            field="amount"
                            style="min-width: 9rem"
                            sortable
                        >
                            <template #header>
                                <span class="block">{{ 'amount' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span 
                                    :class="{
                                        'text-green-500': data.to_wallet?.type === 'cash_wallet',
                                        'text-red-500': data.to_wallet?.type !== 'cash_wallet',
                                    }"
                                >
                                  {{ formatAmount(data.amount, 2) }}
                                </span>
                            </template>
                        </Column>

                        <Column
                            field="status"
                            style="min-width: 7rem"
                            sortable
                        
                        >
                            <template #header>
                                <span class="block">{{ 'status' }}</span>
                            </template>
                            <template #body="{ data }">
                                <span>
                                    <Tag :value="$t(`public.${data.status}`)" :severity="getSeverity(data.status)"/>
                                </span>
                            </template>
                        </Column>
                    </template>
                </DataTable>
            </div>
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

            <div class="flex flex-col gap-2 items-center self-stretch">
                <div class="flex self-stretch text-sm text-surface-ground dark:text-white">
                    {{ $t('public.filter_by_type') }}
                </div>
                <Select
                    v-model="filters['type'].value"
                    :options="type"
                    :placeholder="$t('public.select_type')"
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
                        {{ $t(`public.${slotProps.option}`) }}
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
</template>
