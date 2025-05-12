<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ProfileInformation from "@/Pages/Profile/ProfileInformation.vue";
import {Tab, TabList, TabPanel, TabPanels, Tabs} from "primevue";
import {h, onMounted, ref} from "vue";
import ProfileDetail from "@/Pages/Profile/Detail/ProfileDetail.vue";
import SecurityChange from "./Security/SecurityChange.vue";
import PaymentAccount from "./Payment/PaymentAccount.vue";

const props = defineProps({
    user: Object,
});


const tabs = ref([
    {
        title: 'detail',
        component: h(ProfileDetail, {
            user: props.user
        }),
        value: '0'
    },
    {
        title: 'security',
        component: h(SecurityChange),
        value: '1'
    },
    {
        title: 'payment',
        component: h(PaymentAccount),
        value: '2'
    },
]);

const selectedType = ref('detail');
const activeIndex = ref('0');

onMounted(() => {
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
    });

    if(params.tab === 'payment') {
        selectedType.value = 'payment';
        activeIndex.value = '2';
    } else if(params.tab === 'security') {
        selectedType.value = 'security';
        activeIndex.value = '1';
    } else {
        selectedType.value = 'detail';
        activeIndex.value = '0';
    }
});
</script>

<template>
    <AuthenticatedLayout :title="$t('public.profile')">
        <div class="flex flex-col gap-5 items-center self-stretch">
            <!-- Profile Information -->
            <ProfileInformation
                :user="user"
            />

            <!-- Tabs -->
            <div class="flex flex-col w-full">
                <Tabs v-model:value="activeIndex">
                    <TabList>
                        <Tab
                            v-for="tab in tabs"
                            :key="tab.title"
                            :value="tab.value"
                        >
                            {{ $t(`public.${tab.title}`) }}
                        </Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel
                            v-for="tab in tabs"
                            :key="tab.value"
                            :value="tab.value"
                        >
                            <component :is="tab.component" />
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
