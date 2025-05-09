<script setup>
import SidebarLink from '@/Components/Sidebar/SidebarLink.vue'
import SidebarCollapsible from '@/Components/Sidebar/SidebarCollapsible.vue'
import SidebarCollapsibleItem from '@/Components/Sidebar/SidebarCollapsibleItem.vue'
import { sidebarState } from '@/Composables'
import {onMounted, ref, watchEffect} from "vue";
import {usePage} from "@inertiajs/vue3";
import {
    IconLayoutDashboard,
    IconUsers,
    IconUsersGroup,
    IconTemplate,
    IconCategory,
    IconHistory,
    IconClockDollar,
    IconAdjustmentsDollar,
    IconTag,
    IconClipboardData,
    IconPhotoCog,
    IconChartPie,
    IconId,
    IconBusinessplan,
    IconArrowsDiff,
    IconCreditCardPay,
    IconUserDollar,
    IconDownload,
    IconDatabaseDollar,
} from '@tabler/icons-vue';
import SidebarCategoryLabel from "@/Components/Sidebar/SidebarCategoryLabel.vue";
//
// const page = usePage();
// const pendingDepositCounts = ref(page.props.getPendingDepositCount);
// const pendingWithdrawalCounts = ref(page.props.pendingWithdrawalCounts);
//
// const getPendingCounts = async () => {
//     try {
//         const response = await axios.get(route('dashboard.getPendingCounts'));
//         pendingDepositCounts.value = response.data.pendingDepositCounts;
//         pendingWithdrawalCounts.value = response.data.pendingWithdrawalCounts;
//     } catch (error) {
//         console.error('Error pending counts:', error);
//     }
// };
//
// watchEffect(() => {
//     if (usePage().props.toast !== null) {
//         getPendingCounts();
//     }
// });
</script>

<template>
    <nav
        class="relative flex flex-col flex-1 max-h-full gap-1 items-center"
        :class="{
            'p-3': sidebarState.isOpen || sidebarState.isHovered,
            'px-5 py-3': !sidebarState.isOpen && !sidebarState.isHovered,
        }"
    >
        <SidebarCategoryLabel
            :title="'main_menu'"
        />

        <!-- Dashboard -->
        <SidebarLink
            :title="'dashboard'"
            :href="route('dashboard')"
            :active="route().current('dashboard')"
        >
            <template #icon>
                <IconLayoutDashboard :size="20" stroke-width="1.5" />
            </template>
        </SidebarLink>

        <!-- referral -->
        <SidebarCollapsible
            title="referral"
            :active="route().current('referral.*')"
        >
            <template #icon>
                <IconUsersGroup size="20" stroke-width="1.5" />
            </template>
            <SidebarCollapsibleItem
                title="structure"
                :href="route('referral.structure')"
                :active="route().current('referral.structure')"
            />

            <SidebarCollapsibleItem
                title="sales"
                :href="route('referral.sales.salesIndex')"
                :active="route().current('referral.sales.salesIndex')"
            />
        </SidebarCollapsible>

        <!-- download center -->
        <SidebarLink
            :title="'download_center'"
            :href="route('download.index')"
            :active="route().current('download.index')"
        >
            <template #icon>
                <IconDownload :size="20" stroke-width="1.5" />
            </template>
        </SidebarLink>

        <SidebarCategoryLabel
            :title="'trading'"
        />

        <SidebarLink
            :title="'accounts'"
            :href="route('account')"
            :active="route().current('account')"
        >
            <template #icon>
                <IconId :size="20" stroke-width="1.5" />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="'strategy'"
            :href="route('strategy')"
            :active="route().current('strategy')"
        >
            <template #icon>
                <IconBusinessplan :size="20" stroke-width="1.5" />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="'investment'"

        >
            <template #icon>
                <IconDatabaseDollar :size="20" stroke-width="1.5" />
            </template>
        </SidebarLink>

        <SidebarCategoryLabel
            :title="'report'"
        />

        <SidebarLink
            :title="'transaction'"
            :href="route('report.transaction')"
            :active="route().current('report.transaction')"
        >
            <template #icon>
                <IconCreditCardPay :size="20" stroke-width="1.5" />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="'trade_history'"
            :href="route('report.trade_history')"
            :active="route().current('report.trade_history')"
        >
            <template #icon>
                <IconArrowsDiff :size="20" stroke-width="1.5" />
            </template>
        </SidebarLink>

        <SidebarLink
            :title="'bonus'"
            :href="route('report.bonus')"
            :active="route().current('report.bonus')"
        >
            <template #icon>
                <IconUserDollar :size="20" stroke-width="1.5" />
            </template>
        </SidebarLink>
    </nav>
</template>
