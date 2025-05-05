<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DashboardOverview from "@/Pages/Dashboard/DashboardOverview.vue";
import DashboardWallet from "@/Pages/Dashboard/DashboardWallet.vue";

defineProps({
    wallets: Object
})
</script>

<template>
    <AuthenticatedLayout :title="$t('public.dashboard')">
        <div class="flex flex-col gap-5 items-center self-stretch">
            <div class="flex flex-col xl:flex-row items-center gap-5 self-stretch w-full">
                <div class="flex flex-col gap-5 items-center self-stretch w-full">
                    <!-- Overview -->
                    <DashboardOverview />
                </div>

                <!-- Wallets -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-5 items-center self-stretch w-full">
                    <DashboardWallet
                        :wallets="wallets"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
